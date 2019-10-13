/**
 * Welcome to your Workbox-powered service worker!
 *
 * You'll need to register this file in your web app and you should
 * disable HTTP caching for this file too.
 * See https://goo.gl/nhQhGp
 *
 * The rest of the code is auto-generated. Please don't update this file
 * directly; instead, make changes to your Workbox build configuration
 * and re-run your build process.
 * See https://goo.gl/2aRDsh
 */

importScripts("https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js");

self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});

/**
 * The workboxSW.precacheAndRoute() method efficiently caches and responds to
 * requests for URLs in the manifest.
 * See https://goo.gl/S9QRab
 */
self.__precacheManifest = [
  {
    "url": "chat/chat.css",
    "revision": "9aea1447c837fbaf1d955f3c964ae4d4"
  },
  {
    "url": "chat/chat.js",
    "revision": "a017474d2d470d8aca68ce32861e33c7"
  },
  {
    "url": "chat/js.js",
    "revision": "112e1a5d631f15873850cd1809d4e881"
  },
  {
    "url": "chat/socket.io-client/lib/index.js",
    "revision": "5d158151a431a9cb47c7fb2d45c41939"
  },
  {
    "url": "chat/socket.io-client/lib/manager.js",
    "revision": "a2ff80b9ddcd887421e24347f6099870"
  },
  {
    "url": "chat/socket.io-client/lib/on.js",
    "revision": "87064ae3d77fc6cbd8c08f6ac06fcc6b"
  },
  {
    "url": "chat/socket.io-client/lib/socket.js",
    "revision": "82de3149a7c2d635e675013e8ca03134"
  },
  {
    "url": "chat/socket.io-client/lib/url.js",
    "revision": "74aaac848b9584063904d5e2720fa745"
  },
  {
    "url": "chat/socket.io-client/socket.io.js",
    "revision": "c51c7c5b8b52e50aba0278ae98309736"
  },
  {
    "url": "css/css.css",
    "revision": "e4675bf2220d7a19f018be91bcf4ceb0"
  },
  {
    "url": "css/docker.css",
    "revision": "0c0f2e4a3b6bf68a728d1f39e82d0a46"
  },
  {
    "url": "css/imgmodal.css",
    "revision": "4101aefbdd479ff7cbf03e6941336693"
  },
  {
    "url": "css/prism.css",
    "revision": "d1a940299997947281e89c7e91054952"
  },
  {
    "url": "css/sticky.css",
    "revision": "d8a45ba5ecd13bff93b35c9976f56843"
  },
  {
    "url": "favicon.ico",
    "revision": "d41d8cd98f00b204e9800998ecf8427e"
  },
  {
    "url": "images/125.png",
    "revision": "ef40695c016d0c12327f1a75b8191224"
  },
  {
    "url": "images/50.b.png",
    "revision": "75dd92d336936703bd76fb1f7f386801"
  },
  {
    "url": "images/50.png",
    "revision": "5c7b2a2bb3db7dd4b8f5440cf16532a9"
  },
  {
    "url": "index.php",
    "revision": "cb7b75635956a0a2c8039c1afdd6fc93"
  },
  {
    "url": "js/prism.js",
    "revision": "1827a9998d6e6450b973953a9be4b928"
  },
  {
    "url": "js/sticky.js",
    "revision": "4a682b4925338d64749307dd32076678"
  },
  {
    "url": "manifest.json",
    "revision": "be735f088daeb8b9246dfad66bfef6b0"
  },
  {
    "url": "robots.txt",
    "revision": "b6216d61c03e6ce0c9aea6ca7808f7ca"
  },
  {
    "url": "tts/css.css",
    "revision": "20b52c73aee6580907894675591eaa5d"
  },
  {
    "url": "tts/js.js",
    "revision": "d12073330d581a1da241040820ac6a41"
  },
  {
    "url": "web.config",
    "revision": "df72170f1cdffd64352bb4dafbd6efa0"
  }
].concat(self.__precacheManifest || []);
workbox.precaching.precacheAndRoute(self.__precacheManifest, {});
workbox.routing.registerRoute(
  new RegExp('/.*/'),
  new workbox.strategies.StaleWhileRevalidate()
);