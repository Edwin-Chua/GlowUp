<?php include 'adminheader.php'; ?>
<link rel="stylesheet" href="css/manageuser.css">

<!-- Add Delete Confirmation Modal -->
<div class="delete-modal" id="deleteModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Delete Account</h2>
            <span class="close-modal">&times;</span>
        </div>
        <p class="modal-message">Are you sure you want to delete this user? This action cannot be undone.</p>
        <button class="delete-btn" id="confirmDelete">Delete</button>
    </div>
</div>

<div class="manage-user-container">
    <h1 class="main-title">Manage User</h1>
    <div class="table-container">
        <h2 class="section-title">Users</h2>
        <table class="user-table">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Email Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Add database connection
                $conn = mysqli_connect("localhost", "root", "", "glowup");
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                
                $sql = "SELECT studentid, name, email FROM userglowup";
                $result = mysqli_query($conn, $sql);
                
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td style='text-align: center;'>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td><span class='delete-action' data-id='" . $row['studentid'] . "'>delete</span></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Get modal elements
const modal = document.getElementById('deleteModal');
const closeBtn = document.querySelector('.close-modal');
const deleteButtons = document.querySelectorAll('.delete-action');
const confirmDeleteBtn = document.getElementById('confirmDelete');
let userIdToDelete = null;
let rowToDelete = null;

// Add click event to all delete buttons
deleteButtons.forEach(button => {
    button.addEventListener('click', () => {
        modal.style.display = 'block';
        userIdToDelete = button.getAttribute('data-id');
        rowToDelete = button.closest('tr');
    });
});

// Add click event to confirm delete button
confirmDeleteBtn.addEventListener('click', () => {
    if (userIdToDelete) {
        // Send delete request to server
        fetch(`deleteuser.php?id=${userIdToDelete}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                rowToDelete.remove();
            } else {
                alert('Error deleting user');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting user');
        })
        .finally(() => {
            modal.style.display = 'none';
            userIdToDelete = null;
            rowToDelete = null;
        });
    }
});

// Close modal when clicking X button
closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
    rowToDelete = null;
});

// Close modal when clicking outside
window.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.style.display = 'none';
        rowToDelete = null;
    }
});
</script>

<?php 
mysqli_close($conn);
include 'adminfooter.php'; ?> 