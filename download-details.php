<?php
require_once 'functions.php';

if(isset($_POST['submit_url'])) {
	$url = $_POST['link'];
	$parsed = parse_url($url);

	$http_host = $parsed['host'];

	if($http_host == 'youtube.com' || $http_host == 'www.youtube.com' || $http_host == 'youtu.be' || $http_host == 'www.youtu.be') {
		
		parse_str($url, $urlData);
		$video_id = array_values($urlData)[0];
		
		$videoFetchUrl = "http://www.youtube.com/get_video_info?video_id={$video_id}&asv=3&el=detailpage&hl=en_US";

		$videoData = get_val($videoFetchUrl);
		parse_str($videoData, $video_info);
		
		$video_info = json_decode(json_encode($video_info));

		if(!$video_info->status === 'ok') {
			die("This video can't be downloaded.");
		}
		$videoTitle = $video_info->title;
		$videoAuthor = $video_info->author;
		$videoDurationInSec = $video_info->length_seconds;
		$videoDuration = secToDucation($videoDurationInSec);
		$videoViews = $video_info->view_count;
		$videoThumbnail = "http://i.ytimg.com/vi/{$video_id}/default.jpg";

		$streamFormat = explode(',', $video_info->url_encoded_fmt_stream_map);

		if(isset($video_info->adaptive_fmts)) {
			$streamSFormat = explode(',', $video_info->adaptive_fmts);
			$pStreams = parseStream($streamSFormat);
		}
		$cStreams = parseStream($streamFormat);

	} else {
		header("Location: index.php?error=notyoutube");
	}
	
}
?>
<?php require_once 'includes/header.php'; ?>
<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<hr>
			<h4><strong>Title: </strong><?php echo $videoTitle; ?></h4>
			<div class="col-md-4">
				<img src="<?php echo $videoThumbnail; ?>" alt="">
			</div>
			<div class="col-md-8">
				<h5><strong>Channel: </strong><?php echo $videoAuthor; ?></h5>
				<h5><strong>Duration: </strong><?php echo $videoDuration; ?></h5>
				<h5><strong>Views: </strong><?php echo $videoViews; ?></h5>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<hr>
			<div class="panel panel-danger">
				<div class="panel-heading">If your video doesn't start download automatically and starts to paly, please press <strong><code>CTRL + S</code> or right click on the video and select "Save video as..." to download.</strong></div>
			</div>
			<div class="row">
				<div class="panel panel-default">
				<div class="panel-heading"><h3 class="text-center">Video Formats</h3></div>
					<table class="table table-striped text-center">
						<thead>
							<tr>
								<th class="text-center">Type</th>
								<th class="text-center">Size</th>
								<th class="text-center">Quality</th>
								<th class="text-center">Download</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($cStreams as $stream) { ?>
							<?php $stream = json_decode(json_encode($stream)); ?>
							<tr>
								<td><?php echo $stream->type; ?></td>
								<td><?php echo $stream->size; ?></td>
								<td><?php echo $stream->quality; ?></td>
								<td><a class="btn btn-success" href="<?php echo $stream->url & $videoTitle; ?>" target="_blank" download>Download</a></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div> <!-- Video format only -->
				<?php if(isset($pStreams)): ?>
				<div class="panel panel-default">
				<div class="panel-heading"><h3 class="text-center">Video + Audio Formats</h3></div>
					<table class="table table-striped text-center">
						<thead>
							<tr>
								<th class="text-center">Type</th>
								<th class="text-center">Size</th>
								<th class="text-center">Quality</th>
								<th class="text-center">Download</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($pStreams as $stream): ?>
							<?php $stream = json_decode(json_encode($stream)); ?>
							<tr>
								<td><?php echo $stream->type; ?></td>
								<td><?php echo $stream->size; ?></td>
								<td><?php echo $stream->quality; ?></td>
								<td><a class="btn btn-success" href="<?php echo $stream->url & $videoTitle; ?>" target="_blank" download>Download</a></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div> <!-- video + audio format -->
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<?php require_once 'includes/footer.php'; ?>
