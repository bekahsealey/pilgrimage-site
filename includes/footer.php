
		</section>
	</div>
	<footer>
		<ul>
		<li>&copy;
		<?php 
		$startYear = 2013;
		$thisYear = date('Y');
		if ($startYear == $thisYear) {
			echo $startYear;
		} else {
			echo "{$startYear}&#8211;{$thisYear}";
		}
		?> This Website
		</li>
		<li>Site by <a href="http://nmomedia.com">Bekah Sealey at NMO Media & Design Company</a></li>
		<li><a href="/admin/">Log In</a></li>
		</ul>
	</footer>
</div>	
	<?php if(isset($js)) echo $js; ?>
	<script src="/js/move-sidebar.js"></script>
	<script src="/js/global.js"></script>
</body>
</html>