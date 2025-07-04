<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Google Maps")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Google Maps")) ?>
          <?= $this->include('partials/main-nav') ?>

          <!-- ==================================================== -->
          <!-- Start right Content here -->
          <!-- ==================================================== -->

          <div class="page-content">

               <!-- Start Container Fluid -->
               <div class="container">

                    <div class="row">
                         <div class="col-xl-9">
                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title anchor mb-3" id="usage">
                                             Usage<a class="anchor-link" href="#usage">#</a>
                                        </h5>

                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <small class="text-uppercase">Link</small>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="code">
                                                       <pre class="language-html">
                                                            <code>
                                                                 &lt;!-- Google Map Js --&gt;
                                                                 &lt;script src=&quot;/vendor/gmaps/gmaps.min.js&quot;&gt;&lt;/script&gt;
                                                       
                                                                 &lt;!-- Page Js --&gt;
                                                                 &lt;script src=&quot;/js/components/maps-google.js&quot;&gt;&lt;/script&gt;
                                                            </code>
                                                       </pre>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="basic">
                                             Basic Example<a class="anchor-link" href="#basic">#</a>
                                        </h5>
                                        <p class="text-muted">Give textual form controls like <code>&lt;input&gt;</code>s and <code>&lt;textarea&gt;</code>s an upgrade with custom styles, sizing, focus states, and more.</p>
                                        <div class="">
                                             <div class="mb-3">
                                                  <div id="gmaps-basic" class="gmaps"></div>
                                             </div>

                                             <div class="highlight border rounded">
                                                  <div class="highlight-toolbar border-top-0">
                                                       <ul class="nav nav-pills">
                                                            <li class="nav-item">
                                                                 <a href="#homePill" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                      <span class="fs-12 text-uppercase">html</span>
                                                                 </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                 <a href="#profilePill" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                      <span class="fs-12 text-uppercase">javascript</span>
                                                                 </a>
                                                            </li>
                                                       </ul>
                                                       <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                                  </div>
                                                  <div class="tab-content">
                                                       <div class="tab-pane show active" id="homePill">
                                                            <div class="code">
                                                                 <pre class="language-html">
                                                                      <code>
                                                                           &lt;label for=&quot;cleave-date&quot; class=&quot;form-label&quot;&gt;Date&lt;/label&gt;
                                                                           &lt;input type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;DD-MM-YYYY&quot; id=&quot;cleave-date&quot;&gt;
                                                                      </code>
                                                                 </pre>
                                                            </div>
                                                       </div>
                                                       <div class="tab-pane" id="profilePill">
                                                            <div class="code">
                                                                 <pre class="language-javascript">
                                                                      <code>
                                                                           initBasicGoogleMap() {
                                                                                var map = new GMaps({
                                                                                     div: '#gmaps-basic', lat: -12.043333, lng: -77.028333
                                                                                });
                                                                                }
                                                                      </code>
                                                                 </pre>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="google_map">
                                             Markers Google Map<a class="anchor-link" href="#google_map">#</a>
                                        </h5>
                                        <p class="text-muted">Give textual form controls like <code>&lt;input&gt;</code>s and <code>&lt;textarea&gt;</code>s an upgrade with custom styles, sizing, focus states, and more.</p>
                                        <div class="">
                                             <div class="mb-3">
                                                  <div id="gmaps-markers" class="gmaps"></div>
                                             </div>

                                             <div class="highlight border rounded">
                                                  <div class="highlight-toolbar border-top-0">
                                                       <ul class="nav nav-pills">
                                                            <li class="nav-item">
                                                                 <a href="#markers-map-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                      <span class="fs-12 text-uppercase">html</span>
                                                                 </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                 <a href="#markers-map-js" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                      <span class="fs-12 text-uppercase">javascript</span>
                                                                 </a>
                                                            </li>
                                                       </ul>
                                                       <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                                  </div>
                                                  <div class="tab-content">
                                                       <div class="tab-pane show active" id="markers-map-html">
                                                            <div class="code">
                                                                 <pre class="language-html">
                                                                      <code>
                                                                           &lt;label for=&quot;cleave-date&quot; class=&quot;form-label&quot;&gt;Date&lt;/label&gt;
                                                                           &lt;input type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;DD-MM-YYYY&quot; id=&quot;cleave-date&quot;&gt;
                                                                      </code>
                                                                 </pre>
                                                            </div>
                                                       </div>
                                                       <div class="tab-pane" id="markers-map-js">
                                                            <div class="code">
                                                                 <pre class="language-javascript">
                                                                      <code>
                                                                           initMarkerGoogleMap() {
                                                                                var map = new GMaps({
                                                                                    div: '#gmaps-markers', lat: -12.043333, lng: -77.028333
                                                                                });
                                                                            }
                                                                      </code>
                                                                 </pre>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="street_view">
                                             Street View Panoramas Google Map<a class="anchor-link" href="#street_view">#</a>
                                        </h5>
                                        <p class="text-muted">Give textual form controls like <code>&lt;input&gt;</code>s and <code>&lt;textarea&gt;</code>s an upgrade with custom styles, sizing, focus states, and more.</p>
                                        <div class="">
                                             <div class="mb-3">
                                                  <div id="panorama" class="gmaps"></div>
                                             </div>

                                             <div class="highlight border rounded">
                                                  <div class="highlight-toolbar border-top-0">
                                                       <ul class="nav nav-pills">
                                                            <li class="nav-item">
                                                                 <a href="#street-view-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                      <span class="fs-12 text-uppercase">html</span>
                                                                 </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                 <a href="#street-view-js" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                      <span class="fs-12 text-uppercase">javascript</span>
                                                                 </a>
                                                            </li>
                                                       </ul>
                                                       <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                                  </div>
                                                  <div class="tab-content">
                                                       <div class="tab-pane show active" id="street-view-html">
                                                            <div class="code">
                                                                 <pre class="language-html">
                                                                      <code>
                                                                           &lt;label for=&quot;cleave-date&quot; class=&quot;form-label&quot;&gt;Date&lt;/label&gt;
                                                                           &lt;input type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;DD-MM-YYYY&quot; id=&quot;cleave-date&quot;&gt;
                                                                      </code>
                                                                 </pre>
                                                            </div>
                                                       </div>
                                                       <div class="tab-pane" id="street-view-js">
                                                            <div class="code">
                                                                 <pre class="language-javascript">
                                                                      <code>
                                                                           initStreetViewGoogleMap() {
                                                                                var panorama = GMaps.createPanorama({
                                                                                     el: '#panorama', lat: 42.3455, lng: -71.0983
                                                                                });
                                                                                }
                                                                      </code>
                                                                 </pre>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="map_types">
                                             Google Map Types<a class="anchor-link" href="#map_types">#</a>
                                        </h5>
                                        <p class="text-muted">Give textual form controls like <code>&lt;input&gt;</code>s and <code>&lt;textarea&gt;</code>s an upgrade with custom styles, sizing, focus states, and more.</p>
                                        <div class="">
                                             <div class="mb-3">
                                                  <div id="gmaps-types" class="gmaps"></div>
                                             </div>

                                             <div class="highlight border rounded">
                                                  <div class="highlight-toolbar border-top-0">
                                                       <ul class="nav nav-pills">
                                                            <li class="nav-item">
                                                                 <a href="#map-type-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                      <span class="fs-12 text-uppercase">html</span>
                                                                 </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                 <a href="#map-type-js" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                      <span class="fs-12 text-uppercase">javascript</span>
                                                                 </a>
                                                            </li>
                                                       </ul>
                                                       <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                                  </div>
                                                  <div class="tab-content">
                                                       <div class="tab-pane show active" id="map-type-html">
                                                            <div class="code">
                                                                 <pre class="language-html">
                                                                           <code>
                                                                                &lt;label for=&quot;cleave-date&quot; class=&quot;form-label&quot;&gt;Date&lt;/label&gt;
                                                                                &lt;input type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;DD-MM-YYYY&quot; id=&quot;cleave-date&quot;&gt;
                                                                           </code>
                                                                      </pre>
                                                            </div>
                                                       </div>
                                                       <div class="tab-pane" id="map-type-js">
                                                            <div class="code">
                                                                 <pre class="language-javascript">
                                                                      <code>
                                                                           initMapTypes() {
                                                                                var map = new GMaps({
                                                                                     el: '#gmaps-types', lat: 42.3455, lng: -71.0983, mapTypeControlOptions: {
                                                                                          mapTypeIds: ["hybrid", "roadmap", "satellite", "terrain", "osm", "cloudmade"]
                                                                                     }
                                                                                });
                                                                                map.addMapType("osm", {
                                                                                     getTileUrl: function (coord, zoom) {
                                                                                          return "http://tile.openstreetmap.org/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
                                                                                     }, tileSize: new google.maps.Size(256, 256), name: "OpenStreetMap", maxZoom: 18
                                                                                });
                                                                                map.addMapType("cloudmade", {
                                                                                     getTileUrl: function (coord, zoom) {
                                                                                          return "http://b.tile.cloudmade.com/8ee2a50541944fb9bcedded5165f09d9/1/256/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
                                                                                     }, tileSize: new google.maps.Size(256, 256), name: "CloudMade", maxZoom: 18
                                                                                });
                                                                                map.setMapTypeId("osm");
                                                                                return map;
                                                                                }
                                                                      </code>
                                                                 </pre>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="ultra_light">
                                             Ultra Light With Labels<a class="anchor-link" href="#ultra_light">#</a>
                                        </h5>
                                        <p class="text-muted">Give textual form controls like <code>&lt;input&gt;</code>s and <code>&lt;textarea&gt;</code>s an upgrade with custom styles, sizing, focus states, and more.</p>
                                        <div class="">
                                             <div class="mb-3">
                                                  <div id="ultra-light" class="gmaps"></div>
                                             </div>

                                             <div class="highlight border rounded">
                                                  <div class="highlight-toolbar border-top-0">
                                                       <ul class="nav nav-pills">
                                                            <li class="nav-item">
                                                                 <a href="#ultra-light-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                      <span class="fs-12 text-uppercase">html</span>
                                                                 </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                 <a href="#ultra-light-js" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                      <span class="fs-12 text-uppercase">javascript</span>
                                                                 </a>
                                                            </li>
                                                       </ul>
                                                       <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                                  </div>
                                                  <div class="tab-content">
                                                       <div class="tab-pane show active" id="ultra-light-html">
                                                            <div class="code">
                                                                 <pre class="language-html">
                                                                      <code>
                                                                           &lt;label for=&quot;cleave-date&quot; class=&quot;form-label&quot;&gt;Date&lt;/label&gt;
                                                                           &lt;input type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;DD-MM-YYYY&quot; id=&quot;cleave-date&quot;&gt;
                                                                      </code>
                                                                 </pre>
                                                            </div>
                                                       </div>
                                                       <div class="tab-pane" id="ultra-light-js">
                                                            <div class="code">
                                                                 <pre class="language-javascript">
                                                                      <code>
                                                                           initUltraLightMap() {
                                                                                var map = new GMaps({
                                                                                    div: '#ultra-light', lat: 42.3455, lng: -71.0983, styles: [{
                                                                                        "featureType": "water", "elementType": "geometry", "stylers": [{
                                                                                            "color": "#e9e9e9"
                                                                                        }, {
                                                                                            "lightness": 17
                                                                                        }]
                                                                                    }, {
                                                                                        "featureType": "landscape", "elementType": "geometry", "stylers": [{
                                                                                            "color": "#f5f5f5"
                                                                                        }, {
                                                                                            "lightness": 20
                                                                                        }]
                                                                                    }, {
                                                                                        "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{
                                                                                            "color": "#ffffff"
                                                                                        }, {
                                                                                            "lightness": 17
                                                                                        }]
                                                                                    }, {
                                                                                        "featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [{
                                                                                            "color": "#ffffff"
                                                                                        }, {
                                                                                            "lightness": 29
                                                                                        }, {
                                                                                            "weight": 0.2
                                                                                        }]
                                                                                    }, {
                                                                                        "featureType": "road.arterial", "elementType": "geometry", "stylers": [{
                                                                                            "color": "#ffffff"
                                                                                        }, {
                                                                                            "lightness": 18
                                                                                        }]
                                                                                    }, {
                                                                                        "featureType": "road.local", "elementType": "geometry", "stylers": [{
                                                                                            "color": "#ffffff"
                                                                                        }, {
                                                                                            "lightness": 16
                                                                                        }]
                                                                                    }, {
                                                                                        "featureType": "poi", "elementType": "geometry", "stylers": [{
                                                                                            "color": "#f5f5f5"
                                                                                        }, {
                                                                                            "lightness": 21
                                                                                        }]
                                                                                    }, {
                                                                                        "featureType": "poi.park", "elementType": "geometry", "stylers": [{
                                                                                            "color": "#dedede"
                                                                                        }, {
                                                                                            "lightness": 21
                                                                                        }]
                                                                                    }, {
                                                                                        "elementType": "labels.text.stroke", "stylers": [{
                                                                                            "visibility": "on"
                                                                                        }, {
                                                                                            "color": "#ffffff"
                                                                                        }, {
                                                                                            "lightness": 16
                                                                                        }]
                                                                                    }, {
                                                                                        "elementType": "labels.text.fill", "stylers": [{
                                                                                            "saturation": 36
                                                                                        }, {
                                                                                            "color": "#333333"
                                                                                        }, {
                                                                                            "lightness": 40
                                                                                        }]
                                                                                    }, {
                                                                                        "elementType": "labels.icon", "stylers": [{
                                                                                            "visibility": "off"
                                                                                        }]
                                                                                    }, {
                                                                                        "featureType": "transit", "elementType": "geometry", "stylers": [{
                                                                                            "color": "#f2f2f2"
                                                                                        }, {
                                                                                            "lightness": 19
                                                                                        }]
                                                                                    }, {
                                                                                        "featureType": "administrative", "elementType": "geometry.fill", "stylers": [{
                                                                                            "color": "#fefefe"
                                                                                        }, {
                                                                                            "lightness": 20
                                                                                        }]
                                                                                    }, {
                                                                                        "featureType": "administrative", "elementType": "geometry.stroke", "stylers": [{
                                                                                            "color": "#fefefe"
                                                                                        }, {
                                                                                            "lightness": 17
                                                                                        }, {
                                                                                            "weight": 1.2
                                                                                        }]
                                                                                    }]
                                                                                });
                                                                            }
                                                                      </code>
                                                                 </pre>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="dark-view">
                                             Dark<a class="anchor-link" href="#dark-view">#</a>
                                        </h5>
                                        <p class="text-muted">Give textual form controls like <code>&lt;input&gt;</code>s and <code>&lt;textarea&gt;</code>s an upgrade with custom styles, sizing, focus states, and more.</p>
                                        <div class="">
                                             <div class="mb-3">
                                                  <div id="dark" class="gmaps"></div>
                                             </div>

                                             <div class="highlight border rounded">
                                                  <div class="highlight-toolbar border-top-0">
                                                       <ul class="nav nav-pills">
                                                            <li class="nav-item">
                                                                 <a href="#dark-view-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                      <span class="fs-12 text-uppercase">html</span>
                                                                 </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                 <a href="#dark-view-js" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                      <span class="fs-12 text-uppercase">javascript</span>
                                                                 </a>
                                                            </li>
                                                       </ul>
                                                       <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                                  </div>
                                                  <div class="tab-content">
                                                       <div class="tab-pane show active" id="dark-view-html">
                                                            <div class="code">
                                                                 <pre class="language-html">
                                                                      <code>
                                                                           &lt;div class=&quot;mb-3&quot;&gt;
                                                                                &lt;div id=&quot;dark&quot; class=&quot;gmaps&quot;&gt;&lt;/div&gt;
                                                                           &lt;/div&gt;
                                                                      </code>
                                                                 </pre>
                                                            </div>
                                                       </div>
                                                       <div class="tab-pane" id="dark-view-js">
                                                            <div class="code">
                                                                 <pre class="language-javascript">
                                                                      <code>
                                                                           var map = new GMaps({
                                                                                div: '#dark', lat: 42.3455, lng: -71.0983, styles: [{
                                                                                "featureType": "all", "elementType": "labels", "stylers": [{
                                                                                     "visibility": "on"
                                                                                }]
                                                                                }, {
                                                                                "featureType": "all", "elementType": "labels.text.fill", "stylers": [{
                                                                                     "saturation": 36
                                                                                }, {
                                                                                     "color": "#000000"
                                                                                }, {
                                                                                     "lightness": 40
                                                                                }]
                                                                                }, {
                                                                                "featureType": "all", "elementType": "labels.text.stroke", "stylers": [{
                                                                                     "visibility": "on"
                                                                                }, {
                                                                                     "color": "#000000"
                                                                                }, {
                                                                                     "lightness": 16
                                                                                }]
                                                                                }, {
                                                                                "featureType": "all", "elementType": "labels.icon", "stylers": [{
                                                                                     "visibility": "off"
                                                                                }]
                                                                                }, {
                                                                                "featureType": "administrative", "elementType": "geometry.fill", "stylers": [{
                                                                                     "color": "#000000"
                                                                                }, {
                                                                                     "lightness": 20
                                                                                }]
                                                                                }, {
                                                                                "featureType": "administrative", "elementType": "geometry.stroke", "stylers": [{
                                                                                     "color": "#000000"
                                                                                }, {
                                                                                     "lightness": 17
                                                                                }, {
                                                                                     "weight": 1.2
                                                                                }]
                                                                                }, {
                                                                                "featureType": "administrative.country", "elementType": "labels.text.fill", "stylers": [{
                                                                                     "color": "#e5c163"
                                                                                }]
                                                                                }, {
                                                                                "featureType": "administrative.locality", "elementType": "labels.text.fill", "stylers": [{
                                                                                     "color": "#c4c4c4"
                                                                                }]
                                                                                }, {
                                                                                "featureType": "administrative.neighborhood", "elementType": "labels.text.fill", "stylers": [{
                                                                                     "color": "#e5c163"
                                                                                }]
                                                                                }, {
                                                                                "featureType": "landscape", "elementType": "geometry", "stylers": [{
                                                                                     "color": "#000000"
                                                                                }, {
                                                                                     "lightness": 20
                                                                                }]
                                                                                }, {
                                                                                "featureType": "poi", "elementType": "geometry", "stylers": [{
                                                                                     "color": "#000000"
                                                                                }, {
                                                                                     "lightness": 21
                                                                                }, {
                                                                                     "visibility": "on"
                                                                                }]
                                                                                }, {
                                                                                "featureType": "poi.business", "elementType": "geometry", "stylers": [{
                                                                                     "visibility": "on"
                                                                                }]
                                                                                }, {
                                                                                "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{
                                                                                     "color": "#e5c163"
                                                                                }, {
                                                                                     "lightness": "0"
                                                                                }]
                                                                                }, {
                                                                                "featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [{
                                                                                     "visibility": "off"
                                                                                }]
                                                                                }, {
                                                                                "featureType": "road.highway", "elementType": "labels.text.fill", "stylers": [{
                                                                                     "color": "#ffffff"
                                                                                }]
                                                                                }, {
                                                                                "featureType": "road.highway", "elementType": "labels.text.stroke", "stylers": [{
                                                                                     "color": "#e5c163"
                                                                                }]
                                                                                }, {
                                                                                "featureType": "road.arterial", "elementType": "geometry", "stylers": [{
                                                                                     "color": "#000000"
                                                                                }, {
                                                                                     "lightness": 18
                                                                                }]
                                                                                }, {
                                                                                "featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [{
                                                                                     "color": "#575757"
                                                                                }]
                                                                                }, {
                                                                                "featureType": "road.arterial", "elementType": "labels.text.fill", "stylers": [{
                                                                                     "color": "#ffffff"
                                                                                }]
                                                                                }, {
                                                                                "featureType": "road.arterial", "elementType": "labels.text.stroke", "stylers": [{
                                                                                     "color": "#2c2c2c"
                                                                                }]
                                                                                }, {
                                                                                "featureType": "road.local", "elementType": "geometry", "stylers": [{
                                                                                     "color": "#000000"
                                                                                }, {
                                                                                     "lightness": 16
                                                                                }]
                                                                                }, {
                                                                                "featureType": "road.local", "elementType": "labels.text.fill", "stylers": [{
                                                                                     "color": "#999999"
                                                                                }]
                                                                                }, {
                                                                                "featureType": "transit", "elementType": "geometry", "stylers": [{
                                                                                     "color": "#000000"
                                                                                }, {
                                                                                     "lightness": 19
                                                                                }]
                                                                                }, {
                                                                                "featureType": "water", "elementType": "geometry", "stylers": [{
                                                                                     "color": "#000000"
                                                                                }, {
                                                                                     "lightness": 17
                                                                                }]
                                                                                }]
                                                                           });
                                                                      </code>
                                                                 </pre>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div> <!-- end card body -->
                              </div> <!-- end card -->
                         </div> <!-- end col -->

                         <div class="col-xl-3">
                              <div class="card docs-nav">
                                   <ul class="nav bg-transparent flex-column">
                                        <li class="nav-item">
                                             <a href="#usage" class="nav-link">Usage</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#basic" class="nav-link">Basic</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#google_map" class="nav-link">Markers Google Map</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#street_view" class="nav-link">Street View Panoramas Google Map</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#map_types" class="nav-link">Google Map Types</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#ultra_light" class="nav-link">Ultra Light With Labels</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#dark-view" class="nav-link">Dark</a>
                                        </li>
                                   </ul>
                              </div>
                         </div>
                    </div> <!-- end row -->
               </div>
               <!-- End Container Fluid -->

               <?= $this->include("partials/footer") ?>

          </div>
          <!-- ==================================================== -->
          <!-- End Page Content -->
          <!-- ==================================================== -->

     </div>
     <!-- END Wrapper -->

     <?= $this->include("partials/vendor-scripts") ?>

     <!-- Google Maps API -->
     <script src="http://maps.google.com/maps/api/js"></script>

     <!-- Google Map Js -->
     <script src="/vendor/gmaps/gmaps.min.js"></script>

     <!-- Google Map Demo Js -->
     <script src="/js/components/maps-google.js"></script>

</body>

</html>