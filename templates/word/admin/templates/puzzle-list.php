<?php if (!defined('BASE_PATH')) die("No direct access allowed");

    include_once(dirname(dirname(__FILE__)). '/include/Storage.php');

    $puzzle = new Puzzle;

    # Pagination configuration
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $pages = ceil($puzzle->countAll() / $config->per_page);
?>
<div class="span12">

    <h1>Puzzle list</h1>

    <!-- PUZZLE LIST -->
    <table class="table table-bordered table-striped table-condensed">
        <thead>
            <tr>
                <th>Puzzle Name</th>
                <th>Link</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($puzzle->getAll($page) as $item): ?>
        <tr>
            <td><?php echo $item->name; ?></td>
            <td><input type="text" value="../?puzzle=<?php echo $item->id; ?>"></td>
            <td>
                <!-- EDIT BUTTON -->
                <a class="btn btn-primary" href="?edit=<?php echo $item->id; ?>">
                    <i class="icon-edit"></i> Edit
                </a>

                <!-- DELETE BUTTON -->
                <button data-puzzle-name="<?php echo $item->name; ?>" class="btn btn-danger">
                    <i class="icon-trash"></i> Delete
                </button>

                <!-- PLAY BUTTON -->
                <a class="btn btn-action" href="../?puzzle=<?php echo $item->id; ?>">
                    <i class="icon-play"></i> Play
                </a>

                <form style="display: inline" action="?delete" method="post">
                    <input type="hidden" name="id" value="<?php echo $item->id; ?>">
                </form>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
    </table>

    <!-- DELETE DIALOG -->
    <div id="delete-confirm" class="modal hide fade">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Delete <span class="puzzle-name"></span></h3>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete <span class="label label-important puzzle-name"></span>?</p>
      </div>
      <div class="modal-footer">
        <a href="#" data-dismiss="modal" class="btn">Cancel</a>
        <a href="#" id="confirm" class="btn btn-primary">Delete</a>
      </div>
    </div>

    <!-- PAGINATION -->
    <div class="pagination pagination-centered">
        <ul>
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <?php $class = $i == $page ? "active" : ""; ?>
            <li class="<?php echo $class; ?>">
                <a href="?page=<?php echo $i ?>"><?php echo $i ?></a>
            </li>
        <?php endfor ?>
        </ul>
    </div>
</div>

<script type="text/javascript">
$(function(){
    var $modal = $("#delete-confirm").modal('hide'),
        $puzzle_name = $(".puzzle-name"),
        anchor = document.createElement("a"),
        current_button;

    $(".table").on("click", "button", function(){
        current_button = this;
        $puzzle_name.text($(this).data("puzzle-name"));
        $modal.modal('show');
    })
    .on("click", "input", function(){ this.select(); });

    $("#confirm").click(function(){
        $(current_button).parent().find("form").submit();
    });

    $("table td input[type!=hidden]").each(function(){
        anchor.href = this.value;
        this.value = anchor.href;
    });
})
</script>
