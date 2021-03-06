

<script type="text/javascript" src="<?php assest('lib/jquery.min.js') ?>"></script>
<!-- Bootstrap Lib -->

<link href="https://fonts.googleapis.com/css?family=Exo 2" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="<?php assest('lib/bootstrap.min.css') ?>">
<link rel="stylesheet" href="http://localhost/project/Online-Judge/style/lib/font-awesome/css/font-awesome.css">

<link rel="stylesheet" type="text/css" href="<?php assest('css/style.css') ?>">
<script type="text/javascript" src="<?php assest('lib/bootstrap.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/ace.js"></script>

<?php
$infoData = json_decode(file_get_contents("info.json"), true);
$version  = $infoData['version'];
?>

<script type="text/javascript">
	var gitInfoUrl = "<?php echo $infoData['github-info-file']; ?>";
	var currentVersion = "<?php echo $version; ?>";
</script>

<!DOCTYPE html>
<html>
<head>
	<title>CoderOJ Checker</title>
</head>
<body>

	<div class="containerr">
		<div class="row">
			<div class="col-md-12">
				<div class="topTitleArea">
					<div class="topTitle">
						CoderOJ<strong color="#ced6e0;"></strong>
						<span class="topSubTitle">Checker
							<span class="pull-right" id="versionBtnArea" style="display: none"><button onclick="updateVersion()" id="updateVersionBtn"></button></span>
						</span>

					</div>
				</div>

			</div>

			<?php include view("checker_editor.php");?>


		</div>
		<div class="col-md-12">
			<div class="footer">
				<?php echo "Compiler Version: $version"; ?>
			</div>
		</div>
	</div>
</body>
</html>

<script type="text/javascript" src="<?php assest('js/checker.js') ?>"></script>