<style type="text/css">
body {
  background: white;
}
#loader-container {
  width: 100%;
  height: 100%;
  position: fixed;
  background: rgba(180, 180, 180, 1);
  z-index: 100;
  margin: 0;
  padding: 0;
  top: 0;
  left: 0;
}
.loader {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translateX(-50%) translateY(-50%);
  width: 64px;
  height: 64px;
  margin: auto;
}
.loader .circle {
  position: absolute;
  width: 48px;
  height: 48px;
  opacity: 0;
  transform: rotate(225deg);
  animation-iteration-count: infinite;
  animation-name: orbit;
  animation-duration: 5.5s;
}
.loader .circle:after {
  content: '';
  position: absolute;
  width: 8px;
  height: 8px;
  border-radius: 4px;
  background: #44aacc;
  box-shadow: 0 0 9px rgba(255, 255, 255, .7);
}
.loader .circle:nth-child(2) {
  animation-delay: 240ms;
}
.loader .circle:nth-child(3) {
  animation-delay: 480ms;
}
.loader .circle:nth-child(4) {
  animation-delay: 720ms;
}
.loader .circle:nth-child(5) {
  animation-delay: 960ms;
}
@keyframes orbit {
  0% {
    transform: rotate(225deg);
    opacity: 1;
    animation-timing-function: ease-out;
  }
  7% {
    transform: rotate(345deg);
    animation-timing-function: linear;
  }
  30% {
    transform: rotate(455deg);
    animation-timing-function: ease-in-out;
  }
  39% {
    transform: rotate(690deg);
    animation-timing-function: linear;
  }
  70% {
    transform: rotate(815deg);
    opacity: 1;
    animation-timing-function: ease-out;
  }
  75% {
    transform: rotate(945deg);
    animation-timing-function: ease-out;
  }
  76% {
    transform: rotate(945deg);
    opacity: 0;
  }
  100% {
    transform: rotate(945deg);
    opacity: 0;
  }
}
</style>
<div id='loader-container'>
  <div class='loader'>
    <div class='circle'></div>
    <div class='circle'></div>
    <div class='circle'></div>
    <div class='circle'></div>
    <div class='circle'></div>
  </div>
</div>