var baseURL = "http://nhipsinhhoc.vn/youtube/";
if (typeof isEmbed == 'undefined') isEmbed = 0;
if (window.location.hostname != "localhost" && 1) {
  //if (window.location.protocol != "https:") window.location.replace("https:" + window.location.href.substring(window.location.protocol.length));
  if (location.hostname.indexOf("nhipsinhhoc.vn") == -1) window.location.replace(baseURL + location.hash);
  if ((window.top !== window.self) && isEmbed == 0) window.top.location.replace(window.self.location.href);
  if ((window.top == window.self) && isEmbed) window.top.location.replace(baseURL + location.hash);
}

var coolGuys = ['PewDiePie', 'iisuperwomanii', 'MarquesBrownlee', 'YouTube', 'TheFineBros', 'SkyDoesMinecraft', 'markipliergame', 'theellenshow'];
// var APIkeys = ["AIzaSyC0QYUSKEwHaRVz4NKpT1SLbkVMT1o5cM8"];
var APIkeys = ["AIzaSyBDk4i0LoPrtVtQpf-arHH3umEgHuF1LmE"];
var username = coolGuys[Math.floor(Math.random() * coolGuys.length)];
var rawInput = username;
var keyIndex = 0;
var darkTheme;

Array.prototype.shuffle = function() {
  var i = this.length,
    j, temp;
  if (i == 0) return this;
  while (--i) {
    j = Math.floor(Math.random() * (i + 1));
    temp = this[i];
    this[i] = this[j];
    this[j] = temp;
  }
  return this;
}
APIkeys.shuffle();

var getText = function(url, callback) {
  var request = new XMLHttpRequest();
  request.onreadystatechange = function() {
    if (request.readyState == 4) {
      if (request.status == 200) callback(request.responseText);
      else {
        callback("nex");
      }
    }
  };
  request.open('GET', url);
  request.send();
}
var changeText = function(elem, changeVal) {
  if ('textContent' in elem) {
    elem.textContent = changeVal;
  } else {
    elem.innerText = changeVal;
  }
}
var hasClass = function(elem, className) {
    return new RegExp(' ' + className + ' ').test(' ' + elem.className + ' ');
}
var addClass = function(elem, className) {
    if (!hasClass(elem, className)) {
        elem.className += ' ' + className;
    }
}
var removeClass = function(elem, className) {
    var newClass = ' ' + elem.className.replace(/[\t\r\n]/g, ' ') + ' ';
    if (hasClass(elem, className)) {
        while (newClass.indexOf(' ' + className + ' ') >= 0) {
            newClass = newClass.replace(' ' + className + ' ', ' ');
        }
        elem.className = newClass.replace(/^\s+|\s+$/g, '');
    }
}
var readStorage = function() {
	if(localStorage.getItem("darkTheme") == 'true') toggleDark();
	if(localStorage.getItem("milestone") == 'true') toggleMilestones();
	if(localStorage.getItem("immersive") == 'true') toggleImmersive();
}
var toggleDark = function() {
	var html = document.body.parentElement;
	if(hasClass(html, 'dark')) {
		removeClass(html, 'dark');
		darkTheme.checked = false;
	} else {
		addClass(html, 'dark');
		darkTheme.checked = true;
	}
	localStorage.setItem("darkTheme", darkTheme.checked);
}

var update = {};
update.name = function(name) {
  document.title = name + "'s Realtime Subscriber Count on YouTube";
  changeText(document.querySelector("#name"), name);
}
update.queryName = function() {
  getText("https://www.googleapis.com/youtube/v3/search?part=snippet&q=" + encodeURIComponent(rawInput) + "&type=channel&maxResults=1&key=" + update.getKey(), function(e) {
    if (e == "nex") {
      update.queryName();
      return;
    }
    e = JSON.parse(e);
    if (e.pageInfo.totalResults < 1) {
      newUsername("#Music", "Could not find any channel with that name. ");
      return;
    }
    var n = e.items[0].snippet.title;
    if (isEmbed) {
      document.getElementById("embedImage").src = e.items[0].snippet.thumbnails.default.url;
    }
    update.name(n);
  })
}
update.isLive = 0;
update.live = function() {
  var reqType = (username.length >= 24 && username.substr(0, 2).toUpperCase() == "UC") ? "id" : "forUsername";
  var url = "https://www.googleapis.com/youtube/v3/channels?part=statistics&" + reqType + "=" + username + "&key=" + update.getKey();
  getText(url, function(e) {
    if (e == "nex") {
      return; // pass it on
    }
    e = JSON.parse(e);
    var subscriberCount = e.items[0].statistics.subscriberCount;
    var videoCount = e.items[0].statistics.videoCount;
    var viewCount = e.items[0].statistics.viewCount;
    if (!update.isLive) {
      new Odometer({
        el: document.querySelector(".count_live"),
        value: subscriberCount,
        format: '(,ddd)',
        theme: 'minimal'
      });
      new Odometer({
        el: document.querySelector(".count_yt"),
        value: videoCount,
        format: '(,ddd)',
        theme: 'minimal'
      });
      new Odometer({
        el: document.querySelector(".count_view"),
        value: viewCount,
        format: '(,ddd)',
        theme: 'minimal'
      });
      update.isLive = 1;
    } else {
      changeText(document.querySelector(".count_live"), subscriberCount);
      changeText(document.querySelector(".count_yt"), videoCount);
      changeText(document.querySelector(".count_view"), viewCount);
    }
  });
}
update.parseInput = function(a) {
  rawInput = a;
  getText("https://www.googleapis.com/youtube/v3/search?part=snippet&q=" + encodeURIComponent(a) + "&type=channel&maxResults=1&key=" + update.getKey(), function(e) {
    if (e == "nex") {
      update.parseInput(a);
      return;
    }
    e = JSON.parse(e);
    if (e.pageInfo.totalResults < 1) {
      newUsername("#Music", "Could not find any channel with that name. ");
      return;
    }
    var u = e.items[0].snippet.channelId;
    var n = e.items[0].snippet.title;
    update.reset(u);
    update.name(n);
  })
}
update.share = function(a) {
  var sharableLink = encodeURIComponent(document.getElementById('shareURL').value);
  var facebook = "https://www.facebook.com/dialog/feed?app_id=1473140929606808&display=page&caption=Realtime%20Subscriber%20Count&link=" + sharableLink + "&redirect_uri=" + encodeURIComponent(baseURL + "assets/close.html");
  var twitter = "https://twitter.com/intent/tweet?original_referer=" + sharableLink + "&ref_src=twsrc%5Etfw&text=" + encodeURIComponent(document.title.slice(0, -7) + "@YouTube") + "&tw_p=tweetbutton&via=tungpham42&url=" + sharableLink;
  var youtube = "https://www.youtube.com/" + ((username.length >= 24 && username.substr(0, 2).toUpperCase() == "UC") ? "channel" : "user") + "/" + username;
  switch (a) {
    case 'twtr':
      window.open(twitter);
      break;
    case 'fb':
      window.open(facebook);
      break;
    case 'yt':
      window.open(youtube);
      break;
    default:
      console.log("That's not how it works.");
  }
}
update.getKey = function() {
  keyIndex = (keyIndex + 1) % (APIkeys.length);
  return APIkeys[keyIndex];
}
update.all = function() {
  document.getElementById('shareURL').value = baseURL + "#!/" + username;
  document.getElementById('embedCode').value = '<iframe height="80px" width="300px" frameborder="0" src="' + baseURL + "embed/#!/" + username + '" style="border: 0; width:300px; height:80px; background-color: #FFF;"></iframe>';
  update.queryName();
  update.live();
}
update.reset = function(a) {
  if (!a) return;
  if (a.trim() == username)
    return;
  username = a.trim();
  history.pushState(null, null, "#!/" + username);
  changeText(document.getElementById('name'), "..wait..");
  update.all();
  ga('send', 'pageview', {
    'page': location.pathname + location.search + location.hash,
    'title': document.title
  });
}

function newUsername(a, b) {
  var te = prompt(((typeof(b) == "string") ? b : "") + "Enter new user:", (typeof(a) == "string") ? a : username);
  if (te == null) return;
  if (te.trim() == username || te.trim() == "")
    return;
  if (te)
    update.parseInput(te.trim());
  changeText(document.getElementById('username'), "..wait..");
  history.pushState(null, null, "#!/" + username);
}
window.onpopstate = function() {
  var te = location.hash.split("!/")[1];
  if (te) {
    username = te.trim();
    rawInput = username;
    changeText(document.querySelector('#name'), "..wait..");
    update.queryName();
  }
}
window.onload = function() {
  var te = location.hash.split("!/")[1];
  if (te) {
    username = te.trim();
    rawInput = username;
  } else {
    history.pushState(null, null, "#!/" + username);
  }
  update.all();
  setInterval(update.live, 1 * 1000);

  if (!isEmbed) document.querySelector("#name").onclick = newUsername;

  darkTheme = document.getElementById('darkTheme');
	readStorage();
  // Social!
  (adsbygoogle = window.adsbygoogle || []).push({});
  (adsbygoogle = window.adsbygoogle || []).push({});
  (function(i, s, o, g, r, a, m) {
    i['GoogleAnalyticsObject'] = r;
    i[r] = i[r] || function() {
      (i[r].q = i[r].q || []).push(arguments)
    }, i[r].l = 1 * new Date();
    a = s.createElement(o),
      m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a, m)
  })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
  ga('create', 'UA-50190232-6', 'auto');
  ga('send', 'pageview', {
    'page': location.pathname + location.search + location.hash,
    'title': document.title
  });
}
