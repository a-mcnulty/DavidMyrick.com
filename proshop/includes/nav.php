<div id="exploreBtnCont_top">
			<img id="exploreBtn_blk_top" src="/images/_explore_blk_top.png" />
		</div>
		<div id="openPanel">
			
			<?php
			
				// GET ALL MENU ITEMS FOR TOP NAV SQUARES
				
				$cats = catList("450");
				for ($count = 0; $cat = mysql_fetch_array($cats); ++$count) {
			
			?>
			
				<div class="cell <?php if ($projectDetail['catid'] == $cat['id'] OR $catID == $cat['id']) { ?>pageOn<?php } ?>" data-filter="<?=$cat['slug']?>"><span><?=strtolower(catName($cat['id']));?></span></div>
				
			<?php } ?>			
			
			<div id="rightHanger">
				<img id="arwToggle" src="/images/_arw_up_.png" />
			</div>
			
		</div>
		<div id="openSubPanel" <?php if ($projectID) { ?>class="_GRAY"<?php } ?>>
		
			<?php
			
				// GET ALL PROJECTS FOR EACH MENU SECTION
				
				$cats2 = catList("450");
				for ($count = 0; $cat = mysql_fetch_array($cats2); ++$count) {
				
					// GET ALL PROJECTS IN THIS CATEGORY
					$projectImages = getImages($cat['id']);
					
					for ($pcount = 0; $project = mysql_fetch_array($projectImages); ++$pcount) {
			
			?>	
		
						<div class="cell <?php if ($projectDetail['catid'] == $cat['id']) { ?>_ON<?php } else { ?>_OFF<?php } ?> <?php if ($project['slug'] === $projectSlug) { ?> _COLORME<?php } ?>" data-project="<?=$project['slug']?>" data-tags="<?=$cat['slug']?>" data-slug="<?=$project['slug']?>" title="<?=strtolower($project['title'])?>">
							<div style="background-image: url(<?=getOption("imagePathFront")?>siteThumbs/<?=$project['img']?>); background-repeat: no-repeat; background-size: cover; background-position: 50% 50%; cursor: pointer; width: 100%; height: 100%;"></div>
						</div>
						
			<?php
			
					} // end project loop
					
				} // end category loop
				
			?>
			
		</div>
		<div id="exploreBtnCont">
			<img id="exploreBtn" src="/images/_explore2_.png" />
			<img id="exploreBtn_blk" src="/images/_explore_blk_btm.png" />
		</div>