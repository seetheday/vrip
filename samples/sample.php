<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en">
<head profile="http://gmpg.org/xfn/11">
<meta charset="utf-8" />
<script src="./masonry.pkgd.min.js" type="text/javascript"></script>
</head>
<body>
	<script type="text/javascript">
	var container = document.querySelector('#container');
	var msnry = new Masonry( container, {
  		// options
  		columnWidth: 200,
  		itemSelector: '.item'
	});
	</script>
	<div id="container">
  		<div class="item"><img src='./images/Adding-Dijon-Mustard-to-Balsamic-Vinaigrette.jpg'></div>
  		<div class="item"><img src='./images/Apple-Pie-Ready-for-oven.jpg'></div>
  		<div class="item"><img src='./images/Applesauce-Prune-and-Milled-Flaxseed-Muffins.jpg'></div>
	</div>
</body>
</html>
