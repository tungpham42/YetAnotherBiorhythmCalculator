<link rel="stylesheet" type="text/css"  href="/FWDMSP/global.css"/>
<script type="text/javascript" src="/js/FWDMSP.js"></script>
	
<!-- Setup audio player-->
<script type="text/javascript">
	FWDMSPUtils.onReady(function(){
		new FWDMSP({
			//main settings
			instanceName:"player",
			playlistsId:"playlists",
			mainFolderPath:"/FWDMSP",
			skinPath:"minimal_skin_white",
//			privatePassword:"428c841430ea18a70f7b06525d4b748a",
//			soundCloudAPIKey:"0aff03b3b79c2ac02fd2283b300735bd",
//			showSoundCloudUserNameInTitle:"yes",
			showMainBackground:"no",
			verticalPosition:"bottom",
			horizontalPosition:"center",
			useDeepLinking:"no",
			useYoutube:"yes",
			useVideo:"yes",
			useHEXColorsForSkin:"no",
			normalHEXButtonsColor:"#FF0000",
			selectedHEXButtonsColor:"#00FF00",
			rightClickContextMenu:"default",
			showButtonsToolTips:"yes",
			animate:"no",
			autoPlay:"yes",
			loop:"no",
			shuffle:"no",
			maxWidth:850,
			volume:.042,
			toolTipsButtonsHideDelay:1.5,
			toolTipsButtonFontColor:"#FFFFFF",
			//controller settings
			showControllerByDefault:"yes",
			showThumbnail:"yes",
			showFullScreenButton:"yes",
			showNextAndPrevButtons:"yes",
			showSoundAnimation:"yes",
			showLoopButton:"yes",
			showShuffleButton:"yes",
			showDownloadMp3Button:"yes",
			showBuyButton:"yes",
			showShareButton:"yes",
			expandBackground:"no",
			titleColor:"#000000",
			timeColor:"#919191",
			//controller align and size settings (described in detail in the documentation!)
			controllerHeight:76,
			startSpaceBetweenButtons:9,
			spaceBetweenButtons:8,
			separatorOffsetOutSpace:5,
			separatorOffsetInSpace:9,
			lastButtonsOffsetTop:14,
			allButtonsOffsetTopAndBottom:14,
			titleBarOffsetTop:13,
			mainScrubberOffsetTop:47,
			spaceBetweenMainScrubberAndTime:10,
			startTimeSpace:10,
			scrubbersOffsetWidth:2,
			scrubbersOffestTotalWidth:0,
			volumeButtonAndScrubberOffsetTop:47,
			spaceBetweenVolumeButtonAndScrubber:6,
			volumeScrubberOffestWidth:4,
			scrubberOffsetBottom:10,
			equlizerOffsetLeft:1,
			//playlists window settings
			usePlaylistsSelectBox:"yes",
			showPlaylistsSelectBoxNumbers:"yes",
			showPlaylistsButtonAndPlaylists:"yes",
			showPlaylistsByDefault:"no",
			thumbnailSelectedType:"opacity",
			startAtPlaylist:0,
			startAtTrack:0,
			startAtRandomTrack:"no",
			buttonsMargins:0,
			thumbnailMaxWidth:330, 
			thumbnailMaxHeight:330,
			horizontalSpaceBetweenThumbnails:40,
			verticalSpaceBetweenThumbnails:40,
			mainSelectorBackgroundSelectedColor:"#000000",
			mainSelectorTextNormalColor:"#000000",
			mainSelectorTextSelectedColor:"#FFFFFF",
			mainButtonTextNormalColor:"#6a6a6a",
			mainButtonTextSelectedColor:"#000000",
			//playlist settings
			playTrackAfterPlaylistLoad:"no",
			showPlayListButtonAndPlaylist:"yes",
			showPlayListOnAndroid:"no",
			showPlayListByDefault:"no",
			showPlaylistItemPlayButton:"yes",
			showPlaylistItemDownloadButton:"yes",
			showPlaylistItemBuyButton:"yes",
			forceDisableDownloadButtonForPodcast:"yes",
			forceDisableDownloadButtonForOfficialFM:"yes",
			forceDisableDownloadButtonForFolder:"yes",
			addScrollBarMouseWheelSupport:"yes",
			showTracksNumbers:"yes",
			disableScrubber:"no",
			randomizePlaylist:"no",
			playlistBackgroundColor:"#FFFFFF",
			trackTitleNormalColor:"#6a6a6a",
			trackTitleSelectedColor:"#000000",
			trackDurationColor:"#6a6a6a",
			maxPlaylistItems:10000,
			nrOfVisiblePlaylistItems:12,
			trackTitleOffsetLeft:0,
			playPauseButtonOffsetLeftAndRight:11,
			durationOffsetRight:9,
			downloadButtonOffsetRight:11,
			scrollbarOffestWidth:7,
			//playback rate / speed
			showPlaybackRateButton:"yes",
			defaultPlaybackRate:1, //min - 0.5 / max - 3
			playbackRateWindowTextColor:"#000000",
			//search bar settings
			showSearchBar:"yes",
			showSortButtons:"yes",
			searchInputColor:"#000000",
			searchBarHeight:38,
			inputSearchTextOffsetTop:2,
			inputSearchOffsetLeft:0,
			//password window
			borderColor:"#CDCDCD",
			mainLabelsColor:"#000000",
			secondaryLabelsColor:"#444444",
			textColor:"#777777",
			inputBackgroundColor:"#c0c0c0",
			inputColor:"#333333",
			//opener settings
			openerAlignment:"right",
			showOpener:"yes",
			showOpenerPlayPauseButton:"yes",
			openerEqulizerOffsetLeft:3,
			openerEqulizerOffsetTop:-1,
			//popup settings
			showPopupButton:"no",
			popupWindowBackgroundColor:"#878787",
			popupWindowWidth:850,
			popupWindowHeight:423
		});
	});
</script>
<ul id="playlists" style="display:none;">
	<li data-source="list=PLAE6EB41B485F610A" data-playlist-name="Relax Daily" data-thumbnail-path="/images/relax.jpg">
		<p class="minimalDarkCategoriesTitle"><span class="bold">Relax Daily</span></p>
		<p class="minimalDarkCategoriesType"><span class="bold">Type: </span><span class="minimalDarkCategoriesTypeIn">YOUTUBE PLAYLIST</span></p>
		<p class="minimalDarkCategoriesDescription"><span class="bold">Description: </span>Relax Daily.</p>
	</li>
	<li data-source="list=PLUD30vV2T-cpV9vwNmrMrk49ZfQAjDp50" data-playlist-name="Paris By Night Collection" data-thumbnail-path="/images/Paris_By_Night.jpg">
		<p class="minimalDarkCategoriesTitle"><span class="bold">Paris By Night Collection</span></p>
		<p class="minimalDarkCategoriesType"><span class="bold">Type: </span><span class="minimalDarkCategoriesTypeIn">YOUTUBE PLAYLIST</span></p>
		<p class="minimalDarkCategoriesDescription"><span class="bold">Description: </span>Paris By Night Collection.</p>
	</li>
	<li data-source="list=PLpwKVnqUupycr0jSJiM_NLFdqJci5cRdK" data-playlist-name="PBN Selection" data-thumbnail-path="/images/Paris_By_Night.jpg">
		<p class="minimalDarkCategoriesTitle"><span class="bold">Paris By Night Selection</span></p>
		<p class="minimalDarkCategoriesType"><span class="bold">Type: </span><span class="minimalDarkCategoriesTypeIn">YOUTUBE PLAYLIST</span></p>
		<p class="minimalDarkCategoriesDescription"><span class="bold">Description: </span>Paris By Night Selection.</p>
	</li>
	<li data-source="list=PLVBtJ74CIOK7uwRVAdLj76CzuaCq04FKk" data-playlist-name="Loa Phường" data-thumbnail-path="/images/loaphuong.jpg">
		<p class="minimalDarkCategoriesTitle"><span class="bold">Loa Phường</span></p>
		<p class="minimalDarkCategoriesType"><span class="bold">Type: </span><span class="minimalDarkCategoriesTypeIn">YOUTUBE PLAYLIST</span></p>
		<p class="minimalDarkCategoriesDescription"><span class="bold">Description: </span>Loa Phường.</p>
	</li>
	<li data-source="list=PLjexdUemvcPRsLMPqftkcJw-922krc2zb" data-playlist-name="Ghiền Mì Gõ" data-thumbnail-path="/images/migo.jpg">
		<p class="minimalDarkCategoriesTitle"><span class="bold">Trọn bộ Ghiền Mì Gõ</span></p>
		<p class="minimalDarkCategoriesType"><span class="bold">Type: </span><span class="minimalDarkCategoriesTypeIn">YOUTUBE PLAYLIST</span></p>
		<p class="minimalDarkCategoriesDescription"><span class="bold">Description: </span>Trọn bộ Ghiền Mì Gõ.</p>
	</li>
	<li data-source="list=PLBcAa442MLAo9uhMRPazrSXlDNe2_mcO3" data-playlist-name="Sơn Tùng M-TP" data-thumbnail-path="/images/sontung.jpg">
		<p class="minimalDarkCategoriesTitle"><span class="bold">Những Ca Khúc Mới Nhất Của Sơn Tùng M-TP</span></p>
		<p class="minimalDarkCategoriesType"><span class="bold">Type: </span><span class="minimalDarkCategoriesTypeIn">YOUTUBE PLAYLIST</span></p>
		<p class="minimalDarkCategoriesDescription"><span class="bold">Description: </span>Những Ca Khúc Mới Nhất Của Sơn Tùng M-TP.</p>
	</li>
	<li data-source="list=PLWn8QRqY8ySzALbLto3JwDEQLOfl-pOOR" data-playlist-name="Sound meditation music, Healing music meditation soundtrack" data-thumbnail-path="/FWDMSP/thumbnails/youtube.jpg">
		<p class="minimalDarkCategoriesTitle"><span class="bold">Sound meditation music, Healing music meditation soundtrack</span></p>
		<p class="minimalDarkCategoriesType"><span class="bold">Type: </span><span class="minimalDarkCategoriesTypeIn">YOUTUBE PLAYLIST</span></p>
		<p class="minimalDarkCategoriesDescription"><span class="bold">Description: </span>How to meditate, How to do Guided Meditations, Guided Kriya Yoga Meditation for Health and Healing.</p>
	</li>
</ul>