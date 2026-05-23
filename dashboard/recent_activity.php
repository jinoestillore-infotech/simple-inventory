<?php

    $limit = 5; 
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
    $offset = ($page - 1) * $limit;

    $total_rows = $conn->query("SELECT COUNT(*) as total FROM activity_log")->fetch_assoc()['total'];
    $total_pages = ceil($total_rows / $limit);

    $recent_actions = $conn->query("
        SELECT * FROM activity_log
        ORDER BY created_at DESC
        LIMIT $limit OFFSET $offset
    ");
    ?>

    <div class="pt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Recent Activity</h3>
            <span class="text-muted small">Latest system actions</span>
        </div>

        <?php while ($row = $recent_actions->fetch_assoc()): 
            
            $action = strtolower($row['action']);
            if (str_contains($action, 'add')) {
                $icon = 'bi-plus-circle';
                $color = 'bg-success';
            } elseif (str_contains($action, 'edit') || str_contains($action, 'update')) {
                $icon = 'bi-pencil-square';
                $color = 'bg-warning';
            } elseif (str_contains($action, 'delete')) {
                $icon = 'bi-trash';
                $color = 'bg-danger';
            } else {
                $icon = 'bi-info-circle';
                $color = 'bg-primary';
            }
        ?>

        <div class="card shadow-sm mb-2 activity-card">
            <div class="card-body d-flex align-items-center gap-3">

                <div class="activity-icon <?php echo $color; ?>">
                    <i class="bi <?php echo $icon; ?>"></i>
                </div>

                <div class="flex-grow-1">
                    <div class="fw-semibold">
                        <?php echo htmlspecialchars($row['entity_type']); ?>:
                        <span class="text-primary">
                            <?php echo htmlspecialchars($row['entity_name']); ?>
                        </span>
                    </div>
                    <div class="text-muted small">
                        <?php echo htmlspecialchars($row['action']); ?>
                    </div>
                </div>

                <div class="activity-time text-end">
                    <?php echo date('M d, Y', strtotime($row['created_at'])); ?><br>
                    <?php echo date('h:i A', strtotime($row['created_at'])); ?>
                </div>

            </div>
        </div>

        <?php endwhile; ?>

        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page-1; ?>">Previous</a>
                </li>

                <?php for($i=1; $i<=$total_pages; $i++): ?>
                    <li class="page-item <?php if($i==$page) echo 'active'; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?php if($page >= $total_pages) echo 'disabled'; ?>">
                    <a class="page-link" href="?page=<?php echo $page+1; ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>