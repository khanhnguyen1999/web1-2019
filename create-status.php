<?php 
  require_once 'init.php';
   if (!$currentuser) {
  	header('Location: index.php');
  	exit();
  }
?>
<?php include 'header.php'; ?>
<h1>Đăng status</h1>
	<?php if (isset($_POST['privacy'])): ?>
		<?php 
			$content = $_POST['content_status'];
			$privacy = $_POST['privacy'];

			$_content = nl2br($content);

			$ss = false;

			if(empty($_POST['content_status']) && empty($_POST['filePath']))		
			{	
				return header('Location: index.php');				
			}

			if(isset($_POST['content_status'])) 
			{	
				if($_content != '')		
				{		
					createStatus($currentuser['userID'], $_content, $privacy);	
					$_statusID = getLastStatusIDofU($currentuser['userID']);
					$ss = true;	
				}
				else
				{		
					createStatus($currentuser['userID'], 'NULL', $privacy);	
					$_statusID = getLastStatusIDofU($currentuser['userID']);
					$ss = true;	
				}		
			}

			if (isset($_FILES['files']) && !empty($_POST['filePath']) && $_FILES['files']['name'])
			{
				$ss = false;
				if(count($_FILES['files']['tmp_name']) > 0)
				{
					for($count = 0; $count < count($_FILES['files']['tmp_name']); $count++)
					{
						$image_file = addslashes(file_get_contents($_FILES['files']['tmp_name'][$count]));
						$query = "INSERT INTO teamx_status_images(statusID, image) VALUES ('$_statusID[statusID]', '$image_file')";
						$statement = $db->prepare($query);
						$statement->execute();
					}
					$ss = true;
				}
			}
		?>
		<?php if ($ss): ?>
		<?php header('Location: index.php') ?>
		<?php else: ?>
			<div class="alert alert-danger" role="alert">
				Đăng status thất bại!
			</div>
		<?php endif; ?>
	<?php else: ?>
		<form action="create-status.php" method="POST" enctype="multipart/form-data" id="sessionStorage">
			<div class="form-group">
				<label for="content_status">Nội dung</label>
				<textarea type="text" class="form-control" id="content_status" name="content_status" row="3" aria-describedby="numHelp" placeholder="Nội dung"></textarea>
				<small id="numHelp" class="form-text text-muted">Hãy nhập nội dung!</small>
			</div>
			<div class="form-group">
                <output id="list" name="list"></output>
            </div>
			<div class="form-group">   
                <ul class="list-group list-group-horizontal-xl">
                	<input type="file" multiple class="form-control-file" id="files" name="files[]" style="display:none"/>
                    <label for="files" id="image" style="cursor:pointer;" class="list-group-item_fix_box-status"><i class="fa fa-camera fa-fw"></i><span>&nbsp; Hình Ảnh</span></label>&nbsp;
					<textarea type="text" id="filePath" name="filePath" ></textarea>
					<a href="#" class="list-group-item_fix_box-status"><i class="fa fa-smile-o fa-fw"></i><span>&nbsp; Cảm Xúc</span></a>&nbsp;
                    <a href="#" class="list-group-item_fix_box-status"><i class="fa fa-map-marker fa-fw"></i><span>&nbsp; Check in</span></a>&nbsp;
                    <a href="#" class="list-group-item_fix_box-status"><span>...</span></a>&nbsp;
                </ul>
            </div>
			<div class="dropdown btn-group">
				<select class="btn btn-link-x dropdown-toggle" id="privacy" name="privacy"> 
                    <option selected value="Public"></i>&#xf0ac; Public</option>
                    <option value="Friends">&#xf0c0; Friends</option>
                    <option value="Just me">&#xf007; Just me</option>
                </select>
            </div>		
			<button type="submit" id ="btn_postStatus" class="btn btn-primary">Đăng</button>
		</form>
	<?php endif; ?>
<?php include 'footer.php'; ?>