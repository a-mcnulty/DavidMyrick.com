<?php

$cats = catList();
$logoMark = getFirstImage(719);

?>

<a href="/" class="logo">
	<?php if ($logoMark) { ?>
		<img src="<?= getOption("imagePathFront") ?><?= $logoMark['img'] ?>" class="photo gif" alt="<?= getOption("company") ?>" />
	<?php } else { ?>
		MYRICK
	<?php } ?>
</a>

<div class="menuBtn">
	<div class="ib v-middle">
		<span class="line"></span>
		<span class="line"></span>
		<span class="line"></span>
	</div>
</div>

<header>

	<nav class="takeover">

		<ul>

			<?php for ($count = 1; $cat = mysqli_fetch_array($cats); ++$count) { ?>

				<?php

				if ($cat['pageType'] === "link") {
					$theLink = $cat['link'];
					$theTarget = $cat['target'];
				} else {
					$theLink = "/" . $cat['slug'];
					$theTarget = "_parent";
				}

				$hasSubs = false;
				$parentClass = "";
				$linkClass = "ajax";

				if ($cat['pageType'] === "link") {
					$linkClass = "";
				}

				$catSubs = subcatList($cat['id']);
				if (mysqli_num_rows($catSubs) > 0) {
					$hasSubs = true;
					$firstSubId = getFirstSubID($cat['id']);
					$firstSub = catDetails($firstSubId);
					if ($firstSub['pageType'] === "link") {
						$theLink = $firstSub['link'];
						$theTarget = $firstSub['target'];
					} else {
						$theLink = "/" . $firstSub['slug'];
						$theTarget = "_parent";
					}

					if ($firstSub['pageType'] === "link") {
						$linkClass = "";
					} else {
						$linkClass = "ajax";
					}
				}

				if ($cat['id'] == 717) {
					$theLink = "/";
				}

				?>

				<li class="<?= $parentClass ?>">
					<a href="<?= $theLink ?>" data-id="<?= $cat['id'] ?>" target="<?= $theTarget ?>" class="<?= $linkClass ?> <?php if ($catID == $cat['id'] or $parentID == $cat['id']) { ?>pageOn<?php } ?>">
						<span><?= $cat['name'] ?></span>
					</a>
				</li>

			<?php } ?>

		</ul>

		<div class="background">
			<?php mysqli_data_seek($cats, 0); ?>
			<?php for ($count = 1; $cat = mysqli_fetch_array($cats); ++$count) { ?>
				<?php

				// check for subpages, if found, get first item in first sub
				$catSubs = subcatListAll($cat['id']);
				$theCatId = $cat['id'];
				$theSorter = $cat['sorter'];
				if (mysqli_num_rows($catSubs) > 0) {
					$firstSubId = getFirstSubIDAll($cat['id']);
					$firstSub = catDetails($firstSubId);
					$theCatId = $firstSubId;
					$theSorter = $firstSub['sorter'];
				}

				$firstItem = getFirstImage($theCatId, $theSorter);
				$hasVideo = false;
				$videoStr = '';

				// Only proceed if we have a valid image
				if ($firstItem && isset($firstItem['id'])) {
					$firstItemVideo = getImageVideo($firstItem['id']);
					if ($firstItemVideo && isset($firstItemVideo['hoverFile']) && $firstItemVideo['hoverFile'] != "") {
						$hasVideo = true;
						$videoStr = '<video muted playsinline loop><source src="/videos/' . $firstItemVideo['hoverFile'] . '" /></video>';
					}
					if ($firstItemVideo && isset($firstItemVideo['url_loop_desktop']) && $firstItemVideo['url_loop_desktop'] != "") {
						$hasVideo = true;
						$videoStr = '<video muted playsinline loop class="sizeload" data-hd="' . $firstItemVideo['url_loop_desktop'] . '" data-sd="' . $firstItemVideo['url_loop_mobile'] . '"><source src="" /></video>';
					}
				}

				?>
				<div class="cell" data-id="<?= $cat['id'] ?>">
					<?php if ($hasVideo) { ?>
						<?= $videoStr ?>
					<?php } elseif ($firstItem && isset($firstItem['img'])) { ?>
						<img src="<?= $loaderImg ?>" data-img="<?= $firstItem['img'] ?>" class="photo loadmeview" alt="<?= $cat['name'] ?>" />
					<?php } ?>
				</div>
			<?php } ?>
		</div>

		<!-- New SVG Menu -->
		<div class="svg-menu-container">
			<svg class="dvd-stack" viewBox="0 0 800 900" xmlns="http://www.w3.org/2000/svg">
				<defs>
					<!-- Gradients for 3D effect - Monochromatic with ~60% opacity -->
					<linearGradient id="grayFront" x1="0%" y1="0%" x2="0%" y2="100%">
						<stop offset="0%" style="stop-color:rgba(255, 255, 255, 1);stop-opacity:1" />
						<stop offset="100%" style="stop-color:rgba(255, 255, 255, 1);stop-opacity:1" />
					</linearGradient>
					<linearGradient id="grayTop" x1="0%" y1="0%" x2="0%" y2="100%">
						<stop offset="0%" style="stop-color:rgba(0, 0, 0, 1);stop-opacity:1" />
						<stop offset="100%" style="stop-color:rgba(0, 0, 0, 1);stop-opacity:1" />
					</linearGradient>
					<linearGradient id="yellowFront" x1="0%" y1="0%" x2="0%" y2="100%">
						<stop offset="0%" style="stop-color:rgba(255, 255, 255, 1);stop-opacity:1" />
						<stop offset="100%" style="stop-color:rgba(255, 255, 255, 1);stop-opacity:1" />
					</linearGradient>
					<linearGradient id="yellowTop" x1="0%" y1="0%" x2="0%" y2="100%">
						<stop offset="0%" style="stop-color:rgba(255, 255, 255, 1);stop-opacity:1" />
						<stop offset="100%" style="stop-color:rgba(, 255, 255, 1);stop-opacity:1" />
					</linearGradient>
				</defs>

				<!-- Center alignment guideline (dashed) -->
				<line x1="400" y1="0" x2="400" y2="900" stroke="rgba(138, 135, 130, 0.75)" stroke-width="2" stroke-dasharray="10,10" opacity="0.3"/>

				<!-- Boxes will be dynamically rendered by JavaScript -->
			</svg>
		</div>

	</nav>

</header>

<style>
/* Hide old menu */
nav.takeover > ul {
	display: none !important;
}

/* Hide logo when menu is open */
.menuOn .logo {
	opacity: 0;
}

/* Fix: Make nav.takeover background transparent and non-blocking when menu is closed on homepage */
body.homepage:not(.menuOn) nav.takeover {
	background-color: transparent;
	pointer-events: none;
	display: none;
}

/* Restore nav when menu is open */
body.homepage.menuOn nav.takeover {
	background-color: #ffffffe6;
	pointer-events: all;
	display: block;
}

/* SVG Menu Container */
nav.takeover .svg-menu-container {
	display: none; /* Hidden by default when menu is closed */
	justify-content: center;
	align-items: center;
	width: 100%;
	height: 100%;
	position: relative;
	z-index: 2;
}

/* Show SVG menu only when menu is open */
.menuOn nav.takeover .svg-menu-container {
	display: flex;
}

nav.takeover .dvd-stack {
	width: 100%;
	max-width: 1000px;
	height: auto;
	position: absolute;
}

/* Medium screens */
@media (min-width: 768px) and (max-width: 1439px) {
	nav.takeover .svg-menu-container {
		left: 40%;
	}
}

/* Extra large screens */
@media (min-width: 1440px) {
	nav.takeover .svg-menu-container {
		left: 46%;
	}
}

nav.takeover .box-text {
	font-family: 'Marvin-Regular', sans-serif;
	/* font-size is now set dynamically via JavaScript based on box height */
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0;
}

nav.takeover .dvd-box {
	cursor: pointer;
	transition: opacity 0.3s ease;
}

nav.takeover .dvd-box.wrapping {
	opacity: 0;
}

/* Remove CSS transitions - JavaScript handles all animation */
nav.takeover .dvd-box rect,
nav.takeover .dvd-box polygon,
nav.takeover .dvd-box .trapezoid,
nav.takeover .dvd-box text {
	transition: none;
}

nav.takeover svg {
	user-select: none;
	-webkit-user-select: none;
	touch-action: none;
}

/* Mobile adjustments */
@media (max-width: 767px) {
	nav.takeover .dvd-stack {
		max-width: 100%;
		padding: 0 20px;
	}
}
</style>

<script>
(function() {
	// Menu items data - dynamically generated from live PHP categories
	const items = [
		<?php
		mysqli_data_seek($cats, 0);
		$itemId = 0;
		$menuItems = [];
		for ($count = 1; $cat = mysqli_fetch_array($cats); ++$count) {
			// Determine the URL for this category
			if ($cat['pageType'] === "link") {
				$theLink = $cat['link'];
			} else {
				$theLink = "/" . $cat['slug'];
			}

			// Check for subcategories
			$catSubs = subcatList($cat['id']);
			if (mysqli_num_rows($catSubs) > 0) {
				$firstSubId = getFirstSubID($cat['id']);
				$firstSub = catDetails($firstSubId);
				if ($firstSub['pageType'] === "link") {
					$theLink = $firstSub['link'];
				} else {
					$theLink = "/" . $firstSub['slug'];
				}
			}

			// Escape quotes in label and URL for JavaScript
			$label = addslashes($cat['name']);
			$url = addslashes($theLink);

			$menuItems[] = "{ id: $itemId, label: '$label', url: '$url', catId: {$cat['id']} }";
			$itemId++;
		}
		echo implode(",\n\t\t", $menuItems);
		?>
	];

	// Current rotation state - array of item indices with center position at index 2
	let currentState = [0, 1, 2, 3, 4]; // Middle item (index 2) at center

	// Desktop position configurations - 5 positions for 5 menu items (scaled 1.25x from center)
	const desktopPositionConfigs = [
		{ frontRect: { x: 150, y: 170.575, width: 500, height: 40.85 }, trapezoid: { points: "150,211.425 650,211.425 612.5,223.925 187.5,223.925", onBottom: true }, textX: 175, textY: 191, gradient: 'gray' },
		{ frontRect: { x: 87.5, y: 255.575, width: 625, height: 40.85 }, trapezoid: { points: "87.5,296.425 712.5,296.425 650,308.925 150,308.925", onBottom: true }, textX: 112.5, textY: 276, gradient: 'gray' },
		{ frontRect: { x: 56.25, y: 340.625, width: 687.5, height: 218.75 }, trapezoid: { points: "56.25,340.625 743.75,340.625 743.75,559.375 56.25,559.375", onBottom: true }, textX: 81.25, textY: 450, gradient: 'yellow', hideTrapezoid: true },
		{ frontRect: { x: 87.5, y: 603.575, width: 625, height: 40.85 }, trapezoid: { points: "150,592.325 650,592.325 712.5,603.575 87.5,603.575", onBottom: false }, textX: 112.5, textY: 624, gradient: 'gray' },
		{ frontRect: { x: 150, y: 688.575, width: 500, height: 40.85 }, trapezoid: { points: "187.5,677.325 612.5,677.325 650,688.575 150,688.575", onBottom: false }, textX: 175, textY: 709, gradient: 'gray' }
	];

	// Mobile position configurations - larger boxes with proper spacing (scaled 1.25x from center)
	const mobilePositionConfigs = [
		{ frontRect: { x: 150, y: 94.75, width: 500, height: 64.5 }, trapezoid: { points: "150,159.25 650,159.25 612.5,178 187.5,178", onBottom: true }, textX: 400, textY: 127, gradient: 'gray' },
		{ frontRect: { x: 87.5, y: 200.75, width: 625, height: 64.5 }, trapezoid: { points: "87.5,265.25 712.5,265.25 650,284 150,284", onBottom: true }, textX: 400, textY: 233, gradient: 'gray' },
		{ frontRect: { x: 56.25, y: 327.5, width: 687.5, height: 245 }, trapezoid: { points: "56.25,327.5 743.75,327.5 743.75,572.5 56.25,572.5", onBottom: true }, textX: 400, textY: 450, gradient: 'yellow', hideTrapezoid: true },
		{ frontRect: { x: 87.5, y: 625.75, width: 625, height: 64.5 }, trapezoid: { points: "150,605.75 650,605.75 712.5,625.75 87.5,625.75", onBottom: false }, textX: 400, textY: 658, gradient: 'gray' },
		{ frontRect: { x: 150, y: 732.75, width: 500, height: 64.5 }, trapezoid: { points: "187.5,714 612.5,714 650,732.75 150,732.75", onBottom: false }, textX: 400, textY: 765, gradient: 'gray' }
	];

	// Check screen size and select appropriate config
	let isMobile = window.innerWidth <= 767;
	let positionConfigs = isMobile ? mobilePositionConfigs : desktopPositionConfigs;

	let scrollOffset = 0;
	let isDragging = false;
	let startY = 0;
	let lastY = 0;
	let velocity = 0;
	let lastTimestamp = 0;
	let dragDistance = 0;
	const pixelsPerPosition = 100;
	const clickThreshold = 10;
	let wheelTimeout = null;
	let isWheelScrolling = false;
	let isHandlingClick = false; // Prevent double-triggering on mobile

	// Homepage auto-open menu variables
	const isHomepage = window.location.pathname === '/';
	let autoOpenTimer = null;
	let hasAutoOpened = false;

	function initRender() {
		const svg = document.querySelector('.dvd-stack');
		if (!svg) return;

		currentState.forEach((itemIndex, positionIndex) => {
			const item = items[itemIndex];
			const config = positionConfigs[positionIndex];

			const group = document.createElementNS('http://www.w3.org/2000/svg', 'g');
			group.classList.add('dvd-box');
			group.setAttribute('data-item-id', item.id);
			group.setAttribute('data-cat-id', item.catId);
			group.setAttribute('data-position', positionIndex);
			group.setAttribute('data-url', item.url);

			const trapezoid = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
			trapezoid.classList.add('trapezoid');
			group.appendChild(trapezoid);

			const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
			rect.classList.add('front-rect');
			group.appendChild(rect);

			const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
			text.classList.add('box-text');
			text.setAttribute('fill', '#111');
			text.setAttribute('text-anchor', isMobile ? 'middle' : 'start');
			text.setAttribute('dominant-baseline', 'middle');
			text.textContent = item.label;
			group.appendChild(text);

			svg.appendChild(group);
		});

		updateContinuousPositions();
	}

	function updateBackgroundImage() {
		// Only update menu backgrounds, not homepage videos
		const backgroundContainer = document.querySelector('nav.takeover .background');
		if (!backgroundContainer) return;

		const activeItemIndex = currentState[2];
		const activeItem = items[activeItemIndex];
		const cells = backgroundContainer.querySelectorAll('.cell');

		if (!cells || cells.length === 0) return;

		let matchFound = false;
		cells.forEach(cell => {
			const cellId = parseInt(cell.getAttribute('data-id'));
			if (cellId === activeItem.catId) {
				cell.classList.add('active');
				matchFound = true;
			} else {
				cell.classList.remove('active');
			}
		});

		// Fallback: if no match found, activate the first cell to ensure something shows
		if (!matchFound && cells.length > 0) {
			cells[0].classList.add('active');
		}
	}

	function interpolatePoints(from, to, progress) {
		const fromCoords = from.split(/[\s,]+/).map(Number);
		const toCoords = to.split(/[\s,]+/).map(Number);
		const interpolated = fromCoords.map((fromVal, i) => {
			const toVal = toCoords[i] || fromVal;
			return fromVal + (toVal - fromVal) * progress;
		});
		return interpolated.join(',');
	}

	function cubicBezier(t, p1, p2, p3, p4) {
		const cx = 3 * p1;
		const bx = 3 * (p3 - p1) - cx;
		const ax = 1 - cx - bx;
		const cy = 3 * p2;
		const by = 3 * (p4 - p2) - cy;
		const ay = 1 - cy - by;
		const sampleCurveX = (t) => ((ax * t + bx) * t + cx) * t;
		const sampleCurveY = (t) => ((ay * t + by) * t + cy) * t;
		let t2 = t;
		for (let i = 0; i < 8; i++) {
			const x2 = sampleCurveX(t2) - t;
			if (Math.abs(x2) < 0.001) break;
			const d2 = (3 * ax * t2 + 2 * bx) * t2 + cx;
			if (Math.abs(d2) < 0.000001) break;
			t2 -= x2 / d2;
		}
		return sampleCurveY(t2);
	}

	function getInterpolatedPosition(itemIndex, offset) {
		const currentPos = currentState.indexOf(itemIndex);
		const totalItems = items.length;
		let targetPos = (currentPos + offset) % totalItems;
		if (targetPos < 0) targetPos += totalItems;
		return targetPos;
	}

	function interpolateConfig(pos) {
		const totalPositions = positionConfigs.length;
		let floorPos = Math.floor(pos) % totalPositions;
		let ceilPos = Math.ceil(pos) % totalPositions;
		if (floorPos < 0) floorPos += totalPositions;
		if (ceilPos < 0) ceilPos += totalPositions;
		const fraction = pos - Math.floor(pos);
		const config1 = positionConfigs[floorPos] || positionConfigs[0];
		const config2 = positionConfigs[ceilPos] || positionConfigs[0];
		return {
			frontRect: {
				x: config1.frontRect.x + (config2.frontRect.x - config1.frontRect.x) * fraction,
				y: config1.frontRect.y + (config2.frontRect.y - config1.frontRect.y) * fraction,
				width: config1.frontRect.width + (config2.frontRect.width - config1.frontRect.width) * fraction,
				height: config1.frontRect.height + (config2.frontRect.height - config1.frontRect.height) * fraction
			},
			trapezoid: config1.trapezoid ? {
				points: interpolatePoints(config1.trapezoid.points, config2.trapezoid?.points || config1.trapezoid.points, fraction)
			} : null,
			textX: config1.textX + (config2.textX - config1.textX) * fraction,
			textY: config1.textY + (config2.textY - config1.textY) * fraction,
			gradient: fraction < 0.5 ? config1.gradient : config2.gradient,
			hideTrapezoid: fraction < 0.5 ? config1.hideTrapezoid : config2.hideTrapezoid
		};
	}

	function updateContinuousPositions() {
		const svg = document.querySelector('.dvd-stack');
		if (!svg) return;

		currentState.forEach((itemIndex) => {
			const item = items[itemIndex];
			const targetPos = getInterpolatedPosition(itemIndex, scrollOffset);
			const config = interpolateConfig(targetPos);
			const group = svg.querySelector(`[data-item-id="${item.id}"]`);
			if (!group) return;

			const totalPositions = positionConfigs.length;
			const isWrapping = targetPos > (totalPositions - 0.7);
			group.style.opacity = isWrapping ? '0' : '1';
			group.style.zIndex = isWrapping ? '-1' : '0';

			const rect = group.querySelector('.front-rect');
			const trapezoid = group.querySelector('.trapezoid');
			const text = group.querySelector('text');

			rect.setAttribute('x', config.frontRect.x);
			rect.setAttribute('y', config.frontRect.y);
			rect.setAttribute('width', config.frontRect.width);
			rect.setAttribute('height', config.frontRect.height);
			rect.setAttribute('fill', `url(#${config.gradient}Front)`);

			text.setAttribute('x', config.textX);
			text.setAttribute('y', config.textY);
			text.setAttribute('text-anchor', isMobile ? 'middle' : 'start');

			// Dynamic font sizing based on box height
			const baseFontSize = config.frontRect.height * 0.5;
			const cappedFontSize = Math.min(baseFontSize, 40); // Cap at 40px for very large boxes
			text.setAttribute('font-size', cappedFontSize);

			if (config.trapezoid) {
				trapezoid.setAttribute('points', config.trapezoid.points);
				trapezoid.setAttribute('fill', `url(#${config.gradient}Top)`);
			}

			trapezoid.style.opacity = config.hideTrapezoid ? '0' : '1';
		});
	}

	function handleStart(e) {
		isDragging = true;
		const y = e.clientY || e.touches[0].clientY;
		startY = y;
		lastY = y;
		dragDistance = 0;
		velocity = 0;
		lastTimestamp = performance.now();
	}

	function handleMove(e) {
		if (!isDragging) return;
		e.preventDefault();
		const currentY = e.clientY || e.touches[0].clientY;
		const deltaY = currentY - lastY;
		const currentTime = performance.now();
		const deltaTime = currentTime - lastTimestamp;
		dragDistance += Math.abs(deltaY);
		scrollOffset += deltaY / pixelsPerPosition;
		if (deltaTime > 0) {
			velocity = deltaY / deltaTime;
		}
		lastY = currentY;
		lastTimestamp = currentTime;
		updateContinuousPositions();
	}

	function handleEnd(e) {
		isDragging = false;

		if (dragDistance < clickThreshold) {
			// Prevent double-triggering from both touch and mouse events on mobile
			if (isHandlingClick) {
				return;
			}
			isHandlingClick = true;
			setTimeout(() => { isHandlingClick = false; }, 100);

			scrollOffset = 0;
			updateContinuousPositions();

			const clickY = e.clientY || e.changedTouches?.[0]?.clientY || lastY;
			const svg = document.querySelector('.dvd-stack');
			const svgRect = svg.getBoundingClientRect();
			const relativeY = clickY - svgRect.top;

			let clickedItemId = null;
			const boxes = svg.querySelectorAll('.dvd-box');

			for (const box of boxes) {
				const rect = box.getBoundingClientRect();
				const boxRelativeTop = rect.top - svgRect.top;
				const boxRelativeBottom = rect.bottom - svgRect.top;
				if (relativeY >= boxRelativeTop && relativeY <= boxRelativeBottom) {
					clickedItemId = parseInt(box.getAttribute('data-item-id'));
					break;
				}
			}

			if (clickedItemId !== null) {
				const clickedItemIndex = items.findIndex(item => item.id === clickedItemId);
				const currentPosition = currentState.indexOf(clickedItemIndex);
				const totalItems = items.length;
				const centerPosition = Math.floor(totalItems / 2);

				console.log('Click debug:', {
					clickedItem: items[clickedItemIndex].label,
					clickedItemIndex,
					currentPosition,
					centerPosition,
					isMobile,
					currentState: currentState.map(idx => items[idx].label)
				});

				// If clicking active item, navigate
				if (currentPosition === centerPosition) {
					window.location.href = items[clickedItemIndex].url;
					return;
				}

				// Otherwise, bring to center
				let rotationNeeded = centerPosition - currentPosition;
				const halfItems = Math.floor(totalItems / 2);
				if (rotationNeeded > halfItems) {
					rotationNeeded -= totalItems;
				} else if (rotationNeeded < -halfItems) {
					rotationNeeded += totalItems;
				}

				console.log('Rotation needed:', rotationNeeded);
				animateToOffset(rotationNeeded);
				return;
			}
			return;
		}

		const momentumOffset = velocity * 200;
		const targetOffset = scrollOffset + (momentumOffset / pixelsPerPosition);
		const snappedOffset = Math.round(targetOffset);
		animateToOffset(snappedOffset);
	}

	function handleWheel(e) {
		e.preventDefault();
		if (isDragging) return;
		isWheelScrolling = true;
		const delta = e.deltaY;
		scrollOffset += delta / pixelsPerPosition;
		updateContinuousPositions();

		if (wheelTimeout) {
			clearTimeout(wheelTimeout);
		}

		wheelTimeout = setTimeout(() => {
			isWheelScrolling = false;
			const snappedOffset = Math.round(scrollOffset);
			animateToOffset(snappedOffset);
		}, 150);
	}

	function animateToOffset(targetOffset) {
		const startOffset = scrollOffset;
		const distance = targetOffset - startOffset;
		const duration = Math.min(500, Math.abs(distance) * 200);
		const startTime = performance.now();

		function animate(currentTime) {
			const elapsed = currentTime - startTime;
			const progress = Math.min(elapsed / duration, 1);
			const eased = cubicBezier(progress, 0.4, 0, 0.2, 1);
			scrollOffset = startOffset + distance * eased;
			updateContinuousPositions();

			if (progress < 1) {
				requestAnimationFrame(animate);
			} else {
				syncStateToOffset();
			}
		}
		requestAnimationFrame(animate);
	}

	function syncStateToOffset() {
		const roundedOffset = Math.round(scrollOffset);
		const totalItems = items.length;
		const normalizedOffset = ((roundedOffset % totalItems) + totalItems) % totalItems;
		for (let i = 0; i < normalizedOffset; i++) {
			const last = currentState.pop();
			currentState.unshift(last);
		}
		scrollOffset = 0;
		updateBackgroundImage();
	}

	// Homepage auto-open menu functions
	function openMenu() {
		const menuBtn = document.querySelector('.menuBtn');
		if (menuBtn && !document.body.classList.contains('menuOn')) {
			menuBtn.click();
		}
	}

	function stopAutoOpenTimer() {
		if (autoOpenTimer) {
			clearTimeout(autoOpenTimer);
			autoOpenTimer = null;
		}
	}

	function startAutoOpenTimer() {
		if (!isHomepage || hasAutoOpened) return;

		autoOpenTimer = setTimeout(() => {
			if (!document.body.classList.contains('menuOn')) {
				hasAutoOpened = true;
				openMenu();
			}
		}, 15000); // 15 seconds
	}

	// Homepage scroll gesture detection (wheel or touch swipe)
	function handleHomepageScrollGesture(e) {
		if (!isHomepage || document.body.classList.contains('menuOn')) return;

		// Stop timer on first gesture
		if (!hasAutoOpened) {
			stopAutoOpenTimer();
			hasAutoOpened = true;
		}

		openMenu();
	}

	function handleResize() {
		const newIsMobile = window.innerWidth <= 767;
		// Only update if we crossed the breakpoint
		if (newIsMobile !== isMobile) {
			isMobile = newIsMobile;
			positionConfigs = isMobile ? mobilePositionConfigs : desktopPositionConfigs;
			updateContinuousPositions();
		}
	}

	function init() {
		const svg = document.querySelector('.dvd-stack');
		if (!svg) {
			setTimeout(init, 100);
			return;
		}

		svg.addEventListener('mousedown', handleStart);
		document.addEventListener('mousemove', handleMove);
		document.addEventListener('mouseup', handleEnd);
		svg.addEventListener('touchstart', handleStart, { passive: false });
		document.addEventListener('touchmove', handleMove, { passive: false });
		document.addEventListener('touchend', handleEnd);
		svg.addEventListener('wheel', handleWheel, { passive: false });
		window.addEventListener('resize', handleResize);

		// Homepage auto-open: detect scroll gestures (wheel or touch swipe)
		if (isHomepage) {
			window.addEventListener('wheel', handleHomepageScrollGesture, { passive: true });
			window.addEventListener('touchmove', handleHomepageScrollGesture, { passive: true });
		}

		initRender();
		// Don't call updateBackgroundImage() on page load - only when menu is actually used
		// updateBackgroundImage();

		// Show active menu item's background when menu opens
		const menuBtn = document.querySelector('.menuBtn');
		if (menuBtn) {
			menuBtn.addEventListener('click', () => {
				// Wait for menuOn class to be added
				setTimeout(() => {
					if (document.body.classList.contains('menuOn')) {
						// Find menu item matching current URL and center it
						const currentPath = window.location.pathname;
						const matchingItemIndex = items.findIndex(item => item.url === currentPath);

						if (matchingItemIndex !== -1) {
							const totalItems = items.length;
							const centerPosition = Math.floor(totalItems / 2);

							// Find current position of the matching item
							const currentPosition = currentState.indexOf(matchingItemIndex);

							// Calculate rotation needed to center it
							let rotationNeeded = centerPosition - currentPosition;

							// Normalize rotation to shortest path
							const halfItems = Math.floor(totalItems / 2);
							if (rotationNeeded > halfItems) {
								rotationNeeded -= totalItems;
							} else if (rotationNeeded < -halfItems) {
								rotationNeeded += totalItems;
							}

							// Rotate the state array
							const normalizedRotation = ((rotationNeeded % totalItems) + totalItems) % totalItems;
							for (let i = 0; i < normalizedRotation; i++) {
								const last = currentState.pop();
								currentState.unshift(last);
							}

							// Update positions to show centered item
							updateContinuousPositions();
						}

						updateBackgroundImage();
					}
				}, 50);
			});
		}

		// Homepage auto-open: stop timer when menu opens and start timer
		const originalMenuBtn = document.querySelector('.menuBtn');
		if (originalMenuBtn && isHomepage) {
			const originalClickHandler = originalMenuBtn.onclick;
			originalMenuBtn.addEventListener('click', () => {
				setTimeout(() => {
					if (document.body.classList.contains('menuOn') && !hasAutoOpened) {
						stopAutoOpenTimer();
						hasAutoOpened = true;
					}
				}, 100);
			});

			// Start the auto-open timer
			startAutoOpenTimer();
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
</script>