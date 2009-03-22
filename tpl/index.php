<?xml version="1.0" encoding="UTF-8"?> <!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?php show('title'); ?></title>
		<?php show('styles'); ?>
		<?php show('scripts'); ?>
		<?php show('search'); ?>
		<?php show('feeds'); ?>
		<?php show('description'); ?>
		<?php show('head'); ?>
		</head>
	<body id="<?php show('action'); ?>">
		<div id="wrap">
			<div id="header">
				<?php show('logo'); ?>
				<?php show('nav'); ?>
				<?php show('notice'); ?>
				<?php show('noticeform'); ?>
			</div>
			<div id="core">
				<?php show('localnav'); ?>
				<?php show('bodytext'); ?>
				<div id="aside_primary" class="aside">
					<?php show('export'); ?>
					<?php show('subscriptions'); ?>
					<?php show('subscribers'); ?>
					<?php show('groups'); ?>
					<?php show('statistics'); ?>
					<?php show('cloud'); ?>
					<?php show('groupmembers'); ?>
					<?php show('groupstatistics'); ?>
					<?php show('groupcloud'); ?>
					<?php show('popular'); ?>
					<?php show('groupsbyposts'); ?>
					<?php show('featuredusers'); ?>
					<?php show('groupsbymembers'); ?>
					</div>
				</div>
			<div id="footer">
				<?php show('secondarynav'); ?>
				<?php show('licenses'); ?>
			</div>
			</div>
		</body>
	</html>