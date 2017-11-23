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
			skinPath:"metal_skin_dark",
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
			autoPlay:"no",
			loop:"no",
			shuffle:"no",
			maxWidth:900,
			volume:.24,
			toolTipsButtonsHideDelay:1.5,
			toolTipsButtonFontColor:"#333333",
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
			titleColor:"#FFFFFF",
			timeColor:"#999999",
			//controller align and size settings (described in detail in the documentation!)
			controllerHeight:88,
			startSpaceBetweenButtons:9,
			spaceBetweenButtons:9,
			separatorOffsetOutSpace:4,
			separatorOffsetInSpace:9,
			lastButtonsOffsetTop:10,
			allButtonsOffsetTopAndBottom:16,
			titleBarOffsetTop:15,
			mainScrubberOffsetTop:55,
			spaceBetweenMainScrubberAndTime:6,
			startTimeSpace:10,
			scrubbersOffsetWidth:2,
			scrubbersOffestTotalWidth:-6,
			volumeButtonAndScrubberOffsetTop:48,
			spaceBetweenVolumeButtonAndScrubber:7,
			volumeScrubberOffestWidth:2,
			scrubberOffsetBottom:9,
			equlizerOffsetLeft:2,
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
			mainSelectorBackgroundSelectedColor:"#FFFFFF",
			mainSelectorTextNormalColor:"#FFFFFF",
			mainSelectorTextSelectedColor:"#000000",
			mainButtonTextNormalColor:"#999999",
			mainButtonTextSelectedColor:"#FFFFFF",
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
			playlistBackgroundColor:"#000000",
			trackTitleNormalColor:"#999999",
			trackTitleSelectedColor:"#FFFFFF",
			trackDurationColor:"#999999",
			maxPlaylistItems:100000,
			nrOfVisiblePlaylistItems:12,
			trackTitleOffsetLeft:0,
			playPauseButtonOffsetLeftAndRight:11,
			durationOffsetRight:9,
			downloadButtonOffsetRight:11,
			scrollbarOffestWidth:7,
			//playback rate / speed
			showPlaybackRateButton:"yes",
			defaultPlaybackRate:1, //min - 0.5 / max - 3
			playbackRateWindowTextColor:"#FFFFFF",
			//search bar settings
			showSearchBar:"yes",
			showSortButtons:"yes",
			searchInputColor:"#999999",
			searchBarHeight:44,
			inputSearchTextOffsetTop:2,
			inputSearchOffsetLeft:2,
			//password window
			borderColor:"#333333",
			mainLabelsColor:"#FFFFFF",
			secondaryLabelsColor:"#a1a1a1",
			textColor:"#5a5a5a",
			inputBackgroundColor:"#000000",
			inputColor:"#FFFFFF",
			//opener settings
			openerAlignment:"right",
			showOpener:"yes",
			showOpenerPlayPauseButton:"yes",
			openerEqulizerOffsetLeft:3,
			openerEqulizerOffsetTop:-1,					
			//popup settings
			showPopupButton:"no",
			popupWindowBackgroundColor:"#878787",
			popupWindowWidth:900,
			popupWindowHeight:466
		});
	});
</script>
<ul id="playlists" style="display:none;">
	<li data-source="list=PLGoKVD_nxvNR3_kYQXs36KKTo6er_7avT" data-playlist-name="NHẠC VÀNG" data-thumbnail-path="/images/nhac_vang.jpg">
		<p class="minimalDarkCategoriesTitle"><span class="bold">TUYỂN TẬP NHẠC VÀNG</span></p>
		<p class="minimalDarkCategoriesType"><span class="bold">Type: </span><span class="minimalDarkCategoriesTypeIn">YOUTUBE PLAYLIST</span></p>
		<p class="minimalDarkCategoriesDescription"><span class="bold">Description: </span>TUYỂN TẬP NHẠC VÀNG HAY NHẤT.</p>
	</li>
	<li data-source="list=PLAUgEUsKvpOUDa9wc7QosxGyD6x-btqkc" data-playlist-name="Most Viewed Vpop MV" data-thumbnail-path="/images/vpop.jpg">
		<p class="minimalDarkCategoriesTitle"><span class="bold">Most Viewed Vpop MV</span></p>
		<p class="minimalDarkCategoriesType"><span class="bold">Type: </span><span class="minimalDarkCategoriesTypeIn">YOUTUBE PLAYLIST</span></p>
		<p class="minimalDarkCategoriesDescription"><span class="bold">Description: </span>Most Viewed Vpop MV.</p>
	</li>
	<li data-source="list=PL2HEDIx6Li8hDUxaa-0cLX2tNrx_brV7G" data-playlist-name="Most Viewed Kpop MV" data-thumbnail-path="/images/gangnam.jpg">
		<p class="minimalDarkCategoriesTitle"><span class="bold">Most Viewed Kpop MV</span></p>
		<p class="minimalDarkCategoriesType"><span class="bold">Type: </span><span class="minimalDarkCategoriesTypeIn">YOUTUBE PLAYLIST</span></p>
		<p class="minimalDarkCategoriesDescription"><span class="bold">Description: </span>Most Viewed Kpop MV.</p>
	</li>
	<li data-source="list=PL15EBBC3899C5503A" data-playlist-name="EXO Playlist" data-thumbnail-path="/images/exo.jpg">
		<p class="minimalDarkCategoriesTitle"><span class="bold">EXO Playlist</span></p>
		<p class="minimalDarkCategoriesType"><span class="bold">Type: </span><span class="minimalDarkCategoriesTypeIn">YOUTUBE PLAYLIST</span></p>
		<p class="minimalDarkCategoriesDescription"><span class="bold">Description: </span>EXO Playlist.</p>
	</li>
	<li data-source="list=PL7urOgP8pdZayjA0TuyPkToWift8mFpyS" data-playlist-name="BTS Playlist" data-thumbnail-path="/images/bts.jpg">
		<p class="minimalDarkCategoriesTitle"><span class="bold">BTS Playlist</span></p>
		<p class="minimalDarkCategoriesType"><span class="bold">Type: </span><span class="minimalDarkCategoriesTypeIn">YOUTUBE PLAYLIST</span></p>
		<p class="minimalDarkCategoriesDescription"><span class="bold">Description: </span>BTS Playlist.</p>
	</li>
	<li data-source="list=PLirAqAtl_h2r5g8xGajEwdXd3x1sZh8hC" data-playlist-name="Most Viewed Videos" data-thumbnail-path="/images/gangnam.jpg">
		<p class="minimalDarkCategoriesTitle"><span class="bold">Most Viewed Videos</span></p>
		<p class="minimalDarkCategoriesType"><span class="bold">Type: </span><span class="minimalDarkCategoriesTypeIn">YOUTUBE PLAYLIST</span></p>
		<p class="minimalDarkCategoriesDescription"><span class="bold">Description: </span>Most Viewed Videos.</p>
	</li>
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
	<li data-source="list=PLvpRNZDDyxRLvaIJTc9Li-IgrvsqaJckj" data-playlist-name="FTV Midnight Hot" data-thumbnail-path="/images/ftv.png">
		<p class="minimalDarkCategoriesTitle"><span class="bold">FTV Midnight Hot</span></p>
		<p class="minimalDarkCategoriesType"><span class="bold">Type: </span><span class="minimalDarkCategoriesTypeIn">YOUTUBE PLAYLIST</span></p>
		<p class="minimalDarkCategoriesDescription"><span class="bold">Description: </span>FTV Midnight Hot.</p>
	</li>
	<li data-source="list=PLBcAa442MLAo9uhMRPazrSXlDNe2_mcO3" data-playlist-name="Sơn Tùng M-TP" data-thumbnail-path="/images/sontung.jpg">
		<p class="minimalDarkCategoriesTitle"><span class="bold">Những Ca Khúc Mới Nhất Của Sơn Tùng M-TP</span></p>
		<p class="minimalDarkCategoriesType"><span class="bold">Type: </span><span class="minimalDarkCategoriesTypeIn">YOUTUBE PLAYLIST</span></p>
		<p class="minimalDarkCategoriesDescription"><span class="bold">Description: </span>Những Ca Khúc Mới Nhất Của Sơn Tùng M-TP.</p>
	</li>
	<li data-source="list=PLEyKu1JwbU4vT1PaNypnI8Ux1TjbNvgdz" data-playlist-name="Yêu Ư ? Để Sau - FAPtv" data-thumbnail-path="/images/faptv.jpg">
		<p class="minimalDarkCategoriesTitle"><span class="bold">Yêu Ư ? Để Sau - FAPtv</span></p>
		<p class="minimalDarkCategoriesType"><span class="bold">Type: </span><span class="minimalDarkCategoriesTypeIn">YOUTUBE PLAYLIST</span></p>
		<p class="minimalDarkCategoriesDescription"><span class="bold">Description: </span>Yêu Ư ? Để Sau - FAPtv.</p>
	</li>
	<li data-source="list=PLEyKu1JwbU4uArHCA6p9MuK10Sr49NV3b" data-playlist-name="Quán Cafe Bất Ổn - FAPtv" data-thumbnail-path="/images/faptv.jpg">
		<p class="minimalDarkCategoriesTitle"><span class="bold">Quán Cafe Bất Ổn - FAPtv</span></p>
		<p class="minimalDarkCategoriesType"><span class="bold">Type: </span><span class="minimalDarkCategoriesTypeIn">YOUTUBE PLAYLIST</span></p>
		<p class="minimalDarkCategoriesDescription"><span class="bold">Description: </span>Quán Cafe Bất Ổn - FAPtv.</p>
	</li>
	<li data-source="list=PLWn8QRqY8ySzALbLto3JwDEQLOfl-pOOR" data-playlist-name="Sound meditation music, Healing music meditation soundtrack" data-thumbnail-path="/FWDMSP/thumbnails/youtube.jpg">
		<p class="minimalDarkCategoriesTitle"><span class="bold">Sound meditation music, Healing music meditation soundtrack</span></p>
		<p class="minimalDarkCategoriesType"><span class="bold">Type: </span><span class="minimalDarkCategoriesTypeIn">YOUTUBE PLAYLIST</span></p>
		<p class="minimalDarkCategoriesDescription"><span class="bold">Description: </span>How to meditate, How to do Guided Meditations, Guided Kriya Yoga Meditation for Health and Healing.</p>
	</li>
</ul>