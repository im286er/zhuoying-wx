define(function(require, exports, module) {
	(function($, window, document) {
		var STATE_BEFORECHANGEOFFSET = 'beforeChangeOffset';
		var STATE_AFTERCHANGEOFFSET = 'afterChangeOffset';

		var EVENT_PULLSTART = 'pullstart';
		var EVENT_PULLING = 'pulling';
		var EVENT_BEFORECHANGEOFFSET = STATE_BEFORECHANGEOFFSET;
		var EVENT_AFTERCHANGEOFFSET = STATE_AFTERCHANGEOFFSET;
		var EVENT_DRAGENDAFTERCHANGEOFFSET = 'dragEndAfterChangeOffset';

		var CLASS_TRANSITIONING = $.className('transitioning');
		var CLASS_PULL_TOP_TIPS = $.className('pull-top-tips');
		var CLASS_PULL_BOTTOM_TIPS = $.className('pull-bottom-tips');
		var CLASS_PULL_LOADING = $.className('pull-loading');
		var CLASS_SCROLL = $.className('scroll');

		var CLASS_PULL_TOP_ARROW = $.className('pull-loading') + ' ' + $.className('icon') + ' ' + $.className('icon-pulldown');
		var CLASS_PULL_TOP_ARROW_REVERSE = CLASS_PULL_TOP_ARROW + ' ' + $.className('reverse');
		var CLASS_PULL_TOP_SPINNER = $.className('pull-loading') + ' ' + $.className('spinner');
		var CLASS_HIDDEN = $.className('hidden');

		var SELECTOR_PULL_LOADING = '.' + CLASS_PULL_LOADING;
		$.PullToRefresh = $.Class.extend({
			init: function(element, options) {
				this.element = element;
				this.options = $.extend(true, {
					down: {
						height: 75,
						callback: false,
					},
					up: {
						auto: false,
						offset: 100, //距离底部高度(到达该高度即触发)
						show: true,
						contentdown: '上拉显示更多',
						contentrefresh: '正在加载...',
						contentnomore: '没有更多数据了',
						callback: false
					}
				}, options);
				this.stopped = this.isNeedRefresh = this.isDragging = false;
				this.state = STATE_BEFORECHANGEOFFSET;
				this.isInScroll = this.element.classList.contains(CLASS_SCROLL);
				this.initPullUpTips();

				this.initEvent();
			},
			initEvent: function() {
				if ($.isFunction(this.options.down.callback)) {
					this.element.addEventListener('drag', this);
					this.element.addEventListener('dragend', this);
				}
				if (this.pullUpTips) {
					this.element.addEventListener('dragup', this);
					window.addEventListener('scroll', this);
					if (this.isInScroll) {
						this.element.addEventListener('scrollbottom', this);
					}
				}
			},
			handleEvent: function(e) {
				switch (e.type) {
					case 'drag':
						this._drag(e);
						break;
					case 'dragend':
						this._dragend(e);
						break;
					case 'webkitTransitionEnd':
						this._transitionEnd(e);
						break;
					case 'dragup':
					case 'scroll':
						this._dragup(e);
						break;
					case 'scrollbottom':
						this.pullUpLoading(e);
						break;
				}
			},
			initPullDownTips: function() {
				var self = this;
				if ($.isFunction(self.options.down.callback)) {
					self.pullDownTips = (function() {
						var element = document.querySelector('.' + CLASS_PULL_TOP_TIPS);
						if (element) {
							element.parentNode.removeChild(element);
						}
						if (!element) {
							element = document.createElement('div');
							element.classList.add(CLASS_PULL_TOP_TIPS);
							element.innerHTML = '<div class="mui-pull-top-wrapper"><span class="mui-pull-loading mui-icon mui-icon-pulldown"></span></div>';
							element.addEventListener('webkitTransitionEnd', self);
						}
						self.pullDownTipsIcon = element.querySelector(SELECTOR_PULL_LOADING);
						document.body.appendChild(element);
						return element;
					}());
				}
			},
			initPullUpTips: function() {
				var self = this;
				if ($.isFunction(self.options.up.callback)) {
					self.pullUpTips = (function() {
						var element = self.element.querySelector('.' + CLASS_PULL_BOTTOM_TIPS);
						if (!element) {
							element = document.createElement('div');
							element.classList.add(CLASS_PULL_BOTTOM_TIPS);
							if (!self.options.up.show) {
								element.classList.add(CLASS_HIDDEN);
							}
							element.innerHTML = '<div class="mui-pull-bottom-wrapper"><span class="mui-pull-loading">' + self.options.up.contentdown + '</span></div>';
							self.element.appendChild(element);
						}
						self.pullUpTipsIcon = element.querySelector(SELECTOR_PULL_LOADING);
						return element;
					}());
				}
			},
			_transitionEnd: function(e) {
				if (e.target === this.pullDownTips && this.removing) {
					this.removePullDownTips();
				}
			},
			_dragup: function(e) {
				var self = this;
				if (self.loading) {
					return;
				}
				if (e && e.detail && e.detail.drag) {
					self.isDraggingUp = true;
				} else {
					if (!self.isDraggingUp) { //scroll event
						return;
					}
				}
				if (!self.isDragging) {
					if (self._canPullUp()) {
						self.pullUpLoading(e);
					}
				}
			},
			_canPullUp: function() {
				if (this.removing) {
					return false;
				}
				if (this.isInScroll) {
					var scrollId = this.element.parentNode.getAttribute('data-scroll');
					if (scrollId) {
						var scrollApi = $.data[scrollId];
						return scrollApi.y === scrollApi.maxScrollY;
					}
				}
				return window.pageYOffset + window.innerHeight + this.options.up.offset >= document.documentElement.scrollHeight;
			},
			_canPullDown: function() {
				if (this.removing) {
					return false;
				}
				if (this.isInScroll) {
					var scrollId = this.element.parentNode.getAttribute('data-scroll');
					if (scrollId) {
						var scrollApi = $.data[scrollId];
						return scrollApi.y === 0;
					}
				}
				return document.body.scrollTop === 0;
			},
			_drag: function(e) {
				if (this.loading || this.stopped) {
					e.stopPropagation();
					e.detail.gesture.preventDefault();
					return;
				}
				var detail = e.detail;
				if (!this.isDragging) {
					if (detail.direction === 'down' && this._canPullDown()) {
						this.isDragging = true;
						this.removing = false;
						this.startDeltaY = detail.deltaY;
						$.gestures.session.lockDirection = true; //锁定方向
						$.gestures.session.startDirection = detail.direction;
						this._pullStart(this.startDeltaY);
					}
				}
				if (this.isDragging) {
					e.stopPropagation();
					e.detail.gesture.preventDefault();
					var deltaY = detail.deltaY - this.startDeltaY;
					deltaY = Math.min(deltaY, 1.5 * this.options.down.height);
					this.deltaY = deltaY;
					this._pulling(deltaY);
					var state = deltaY > this.options.down.height ? STATE_AFTERCHANGEOFFSET : STATE_BEFORECHANGEOFFSET;
					if (this.state !== state) {
						this.state = state;
						if (this.state === STATE_AFTERCHANGEOFFSET) {
							this.removing = false;
							this.isNeedRefresh = true;
						} else {
							this.removing = true;
							this.isNeedRefresh = false;
						}
						this['_' + state](deltaY);
					}
					if ($.os.ios && parseFloat($.os.version) >= 8) {
						var clientY = detail.gesture.touches[0].clientY;
						if ((clientY + 10) > window.innerHeight || clientY < 10) {
							this._dragend(e);
							return;
						}
					}
				}
			},
			_dragend: function(e) {
				var self = this;
				if (self.isDragging) {
					self.isDragging = false;
					self._dragEndAfterChangeOffset(self.isNeedRefresh);
				}
				if (self.isPullingUp) {
					if (self.pullingUpTimeout) {
						clearTimeout(self.pullingUpTimeout);
					}
					self.pullingUpTimeout = setTimeout(function() {
						self.isPullingUp = false;
					}, 1000);
				}
			},
			_pullStart: function(startDeltaY) {
				this.pullStart(startDeltaY);
				$.trigger(this.element, EVENT_PULLSTART, {
					api: this,
					startDeltaY: startDeltaY
				});
			},
			_pulling: function(deltaY) {
				this.pulling(deltaY);
				$.trigger(this.element, EVENT_PULLING, {
					api: this,
					deltaY: deltaY
				});
			},
			_beforeChangeOffset: function(deltaY) {
				this.beforeChangeOffset(deltaY);
				$.trigger(this.element, EVENT_BEFORECHANGEOFFSET, {
					api: this,
					deltaY: deltaY
				});
			},
			_afterChangeOffset: function(deltaY) {
				this.afterChangeOffset(deltaY);
				$.trigger(this.element, EVENT_AFTERCHANGEOFFSET, {
					api: this,
					deltaY: deltaY
				});
			},
			_dragEndAfterChangeOffset: function(isNeedRefresh) {
				this.dragEndAfterChangeOffset(isNeedRefresh);
				$.trigger(this.element, EVENT_DRAGENDAFTERCHANGEOFFSET, {
					api: this,
					isNeedRefresh: isNeedRefresh
				});
			},
			removePullDownTips: function() {
				if (this.pullDownTips) {
					try {
						this.pullDownTips.parentNode && this.pullDownTips.parentNode.removeChild(this.pullDownTips);
						this.pullDownTips = null;
						this.removing = false;
					} catch (e) {}
				}
			},
			pullStart: function(startDeltaY) {
				this.initPullDownTips(startDeltaY);
			},
			pulling: function(deltaY) {
				this.pullDownTips.style.webkitTransform = 'translate3d(0,' + deltaY + 'px,0)';
			},
			beforeChangeOffset: function(deltaY) {
				this.pullDownTipsIcon.className = CLASS_PULL_TOP_ARROW;
			},
			afterChangeOffset: function(deltaY) {
				this.pullDownTipsIcon.className = CLASS_PULL_TOP_ARROW_REVERSE;
			},
			dragEndAfterChangeOffset: function(isNeedRefresh) {
				if (isNeedRefresh) {
					this.pullDownTipsIcon.className = CLASS_PULL_TOP_SPINNER;
					this.pullDownLoading();
				} else {
					this.pullDownTipsIcon.className = CLASS_PULL_TOP_ARROW;
					this.endPullDownToRefresh();
				}
			},
			pullDownLoading: function() {
				if (this.loading) {
					return;
				}
				if (!this.pullDownTips) {
					this.initPullDownTips();
					this.dragEndAfterChangeOffset(true);
					return;
				}
				this.loading = true;
				this.pullDownTips.classList.add(CLASS_TRANSITIONING);
				this.pullDownTips.style.webkitTransform = 'translate3d(0,' + this.options.down.height + 'px,0)';
				this.options.down.callback.apply(this);
			},
			pullUpLoading: function(e) {
				if (this.loading || this.finished) {
					return;
				}
				this.loading = true;
				this.isDraggingUp = false;
				this.pullUpTips.classList.remove(CLASS_HIDDEN);
				e && e.detail && e.detail.gesture && e.detail.gesture.preventDefault();
				this.pullUpTipsIcon.innerHTML = this.options.up.contentrefresh;
				this.options.up.callback.apply(this);
			},
			endPullDownToRefresh: function() {
				this.loading = false;
				this.pullUpTips && this.pullUpTips.classList.remove(CLASS_HIDDEN);
				this.pullDownTips.classList.add(CLASS_TRANSITIONING);
				this.pullDownTips.style.webkitTransform = 'translate3d(0,0,0)';
				if (this.deltaY <= 0) {
					this.removePullDownTips();
				} else {
					this.removing = true;
				}
			},
			endPullUpToRefresh: function(finished) {
				if (finished) {
					this.finished = true;
					this.pullUpTipsIcon.innerHTML = this.options.up.contentnomore;
					this.element.removeEventListener('dragup', this);
					window.removeEventListener('scroll', this);
				} else {
					this.pullUpTipsIcon.innerHTML = this.options.up.contentdown;
				}
				this.loading = false;
			},
			setStopped: function(stopped) {
				if (stopped != this.stopped) {
					this.stopped = stopped;
					this.pullUpTips && this.pullUpTips.classList[stopped ? 'add' : 'remove'](CLASS_HIDDEN);
				}
			},
			refresh: function(isReset) {
				if (isReset && this.finished && this.pullUpTipsIcon) {
					this.pullUpTipsIcon.innerHTML = this.options.up.contentdown;
					this.element.addEventListener('dragup', this);
					window.addEventListener('scroll', this);
					this.finished = false;
				}
			}
		});
		$.fn.pullToRefresh = function(options) {
			var pullRefreshApis = [];
			options = options || {};
			this.each(function() {
				var self = this;
				var pullRefreshApi = null;
				var id = self.getAttribute('data-pullToRefresh');
				if (!id) {
					id = ++$.uuid;
					$.data[id] = pullRefreshApi = new $.PullToRefresh(self, options);
					self.setAttribute('data-pullToRefresh', id);
				} else {
					pullRefreshApi = $.data[id];
				}
				if (options.up && options.up.auto) { //如果设置了auto，则自动上拉一次
					pullRefreshApi.pullUpLoading();
				}
				pullRefreshApis.push(pullRefreshApi);
			});
			return pullRefreshApis.length === 1 ? pullRefreshApis[0] : pullRefreshApis;
		}
	})(mui, window, document);

	(function($) {
		var CLASS_PULL_TOP_TIPS = $.className('pull-top-tips');

		$.PullToRefresh = $.PullToRefresh.extend({
			init: function(element, options) {
				this._super(element, options);
				this.options = $.extend(true, {
					down: {
						tips: {
							colors: ['008000', 'd8ad44', 'd00324', 'dc00b8', '017efc'],
							size: 200, //width=height=size;x=y=size/2;radius=size/4
							lineWidth: 15,
							duration: 1000,
							tail_duration: 1000 * 2.5
						}
					}
				}, this.options);
				this.options.down.tips.color = this.options.down.tips.colors[0];
				this.options.down.tips.colors = this.options.down.tips.colors.map(function(color) {
					return {
						r: parseInt(color.substring(0, 2), 16),
						g: parseInt(color.substring(2, 4), 16),
						b: parseInt(color.substring(4, 6), 16)
					};
				});
			},
			initPullDownTips: function() {
				var self = this;
				if ($.isFunction(self.options.down.callback)) {
					self.pullDownTips = (function() {
						var element = document.querySelector('.' + CLASS_PULL_TOP_TIPS);
						if (element) {
							element.parentNode.removeChild(element);
						}
						if (!element) {
							element = document.createElement('div');
							element.classList.add(CLASS_PULL_TOP_TIPS);
							element.innerHTML = '<div class="mui-pull-top-wrapper"><div class="mui-pull-top-canvas"><canvas id="pullDownTips" width="' + self.options.down.tips.size + '" height="' + self.options.down.tips.size + '"></canvas></div></div>';
							element.addEventListener('webkitTransitionEnd', self);
							document.body.appendChild(element);
						}
						self.pullDownCanvas = document.getElementById("pullDownTips");
						self.pullDownCanvasCtx = self.pullDownCanvas.getContext('2d');
						self.canvasUtils.init(self.pullDownCanvas, self.options.down.tips);
						return element;
					}());
				}
			},
			removePullDownTips: function() {
				this._super();
				this.canvasUtils.stopSpin();
			},
			pulling: function(deltaY) {
				var ratio = Math.min(deltaY / (this.options.down.height * 1.5), 1);
				var ratioPI = Math.min(1, ratio * 2);
				this.pullDownTips.style.webkitTransform = 'translate3d(0,' + (deltaY < 0 ? 0 : deltaY) + 'px,0)';
				this.pullDownCanvas.style.opacity = ratioPI;
				this.pullDownCanvas.style.webkitTransform = 'rotate(' + 300 * ratio + 'deg)';
				var canvas = this.pullDownCanvas;
				var ctx = this.pullDownCanvasCtx;
				var size = this.options.down.tips.size;
				ctx.lineWidth = this.options.down.tips.lineWidth;
				ctx.fillStyle = '#' + this.options.down.tips.color;
				ctx.strokeStyle = '#' + this.options.down.tips.color;
				ctx.stroke();
				ctx.clearRect(0, 0, size, size);
				//fixed android 4.1.x
				canvas.style.display = 'none'; // Detach from DOM
				canvas.offsetHeight; // Force the detach
				canvas.style.display = 'inherit'; // Reattach to DOM
				this.canvasUtils.drawArcedArrow(ctx, size / 2 + 0.5, size / 2, size / 4, 0 * Math.PI, 5 / 3 * Math.PI * ratioPI, false, 1, 2, 0.7853981633974483, 25, this.options.down.tips.lineWidth, this.options.down.tips.lineWidth);
			},

			beforeChangeOffset: function(deltaY) {},
			afterChangeOffset: function(deltaY) {},
			dragEndAfterChangeOffset: function(isNeedRefresh) {
				if (isNeedRefresh) {
					this.canvasUtils.startSpin();
					this.pullDownLoading();
				} else {
					this.canvasUtils.stopSpin();
					this.endPullDownToRefresh();
				}
			},
			canvasUtils: (function() {
				var canvasObj = null,
					ctx = null,
					size = 200,
					lineWidth = 15,
					tick = 0,
					startTime = 0,
					frameTime = 0,
					timeLast = 0,
					oldStep = 0,
					acc = 0,
					head = 0,
					tail = 180,
					rad = Math.PI / 180,
					duration = 1000,
					tail_duration = 1000 * 2.5,
					colors = ['35ad0e', 'd8ad44', 'd00324', 'dc00b8', '017efc'],
					rAF = null;

				function easeLinear(currentIteration, startValue, changeInValue, totalIterations) {
					return changeInValue * currentIteration / totalIterations + startValue;
				}

				function easeInOutQuad(currentIteration, startValue, changeInValue, totalIterations) {
					if ((currentIteration /= totalIterations / 2) < 1) {
						return changeInValue / 2 * currentIteration * currentIteration + startValue;
					}
					return -changeInValue / 2 * ((--currentIteration) * (currentIteration - 2) - 1) + startValue;
				}

				function minmax(value, v0, v1) {
					var min = Math.min(v0, v1);
					var max = Math.max(v0, v1);
					if (value < min)
						return min;
					if (value > max)
						return min;
					return value;
				}
				var drawHead = function(ctx, x0, y0, x1, y1, x2, y2, style) {
					'use strict';
					if (typeof(x0) == 'string') x0 = parseInt(x0);
					if (typeof(y0) == 'string') y0 = parseInt(y0);
					if (typeof(x1) == 'string') x1 = parseInt(x1);
					if (typeof(y1) == 'string') y1 = parseInt(y1);
					if (typeof(x2) == 'string') x2 = parseInt(x2);
					if (typeof(y2) == 'string') y2 = parseInt(y2);
					var radius = 3;
					var twoPI = 2 * Math.PI;
					ctx.save();
					ctx.beginPath();
					ctx.moveTo(x0, y0);
					ctx.lineTo(x1, y1);
					ctx.lineTo(x2, y2);
					switch (style) {
						case 0:
							var backdist = Math.sqrt(((x2 - x0) * (x2 - x0)) + ((y2 - y0) * (y2 - y0)));
							ctx.arcTo(x1, y1, x0, y0, .55 * backdist);
							ctx.fill();
							break;
						case 1:
							ctx.beginPath();
							ctx.moveTo(x0, y0);
							ctx.lineTo(x1, y1);
							ctx.lineTo(x2, y2);
							ctx.lineTo(x0, y0);
							ctx.fill();
							break;
						case 2:
							ctx.stroke();
							break;
						case 3:
							var cpx = (x0 + x1 + x2) / 3;
							var cpy = (y0 + y1 + y2) / 3;
							ctx.quadraticCurveTo(cpx, cpy, x0, y0);
							ctx.fill();
							break;
						case 4:
							var cp1x, cp1y, cp2x, cp2y, backdist;
							var shiftamt = 5;
							if (x2 == x0) {
								backdist = y2 - y0;
								cp1x = (x1 + x0) / 2;
								cp2x = (x1 + x0) / 2;
								cp1y = y1 + backdist / shiftamt;
								cp2y = y1 - backdist / shiftamt;
							} else {
								backdist = Math.sqrt(((x2 - x0) * (x2 - x0)) + ((y2 - y0) * (y2 - y0)));
								var xback = (x0 + x2) / 2;
								var yback = (y0 + y2) / 2;
								var xmid = (xback + x1) / 2;
								var ymid = (yback + y1) / 2;
								var m = (y2 - y0) / (x2 - x0);
								var dx = (backdist / (2 * Math.sqrt(m * m + 1))) / shiftamt;
								var dy = m * dx;
								cp1x = xmid - dx;
								cp1y = ymid - dy;
								cp2x = xmid + dx;
								cp2y = ymid + dy;
							}
							ctx.bezierCurveTo(cp1x, cp1y, cp2x, cp2y, x0, y0);
							ctx.fill();
							break;
					}
					ctx.restore();
				};
				var drawArcedArrow = function(ctx, x, y, r, startangle, endangle, anticlockwise, style, which, angle, d, lineWidth, lineRatio) {
					'use strict';
					style = typeof(style) != 'undefined' ? style : 3;
					which = typeof(which) != 'undefined' ? which : 1;
					angle = typeof(angle) != 'undefined' ? angle : Math.PI / 8;
					lineWidth = lineWidth || 1;
					lineRatio = lineRatio || 10;
					d = typeof(d) != 'undefined' ? d : 10;
					ctx.save();
					ctx.lineWidth = lineWidth;
					ctx.beginPath();
					ctx.arc(x, y, r, startangle, endangle, anticlockwise);
					ctx.stroke();
					var sx, sy, lineangle, destx, desty;
					if (which & 1) {
						sx = Math.cos(startangle) * r + x;
						sy = Math.sin(startangle) * r + y;
						lineangle = Math.atan2(x - sx, sy - y);
						if (anticlockwise) {
							destx = sx + 10 * Math.cos(lineangle);
							desty = sy + 10 * Math.sin(lineangle);
						} else {
							destx = sx - 10 * Math.cos(lineangle);
							desty = sy - 10 * Math.sin(lineangle);
						}
						drawArrow(ctx, sx, sy, destx, desty, style, 2, angle, d);
					}
					if (which & 2) {
						sx = Math.cos(endangle) * r + x;
						sy = Math.sin(endangle) * r + y;
						lineangle = Math.atan2(x - sx, sy - y);
						if (anticlockwise) {
							destx = sx - 10 * Math.cos(lineangle);
							desty = sy - 10 * Math.sin(lineangle);
						} else {
							destx = sx + 10 * Math.cos(lineangle);
							desty = sy + 10 * Math.sin(lineangle);
						}
						drawArrow(ctx, sx - lineRatio * Math.sin(endangle), sy + lineRatio * Math.cos(endangle), destx - lineRatio * Math.sin(endangle), desty + lineRatio * Math.cos(endangle), style, 2, angle, d)
					}
					ctx.restore();
				}
				var drawArrow = function(ctx, x1, y1, x2, y2, style, which, angle, d) {
					'use strict';
					if (typeof(x1) == 'string') x1 = parseInt(x1);
					if (typeof(y1) == 'string') y1 = parseInt(y1);
					if (typeof(x2) == 'string') x2 = parseInt(x2);
					if (typeof(y2) == 'string') y2 = parseInt(y2);
					style = typeof(style) != 'undefined' ? style : 3;
					which = typeof(which) != 'undefined' ? which : 1;
					angle = typeof(angle) != 'undefined' ? angle : Math.PI / 8;
					d = typeof(d) != 'undefined' ? d : 10;
					var toDrawHead = typeof(style) != 'function' ? drawHead : style;
					var dist = Math.sqrt((x2 - x1) * (x2 - x1) + (y2 - y1) * (y2 - y1));
					var ratio = (dist - d / 3) / dist;
					var tox, toy, fromx, fromy;
					if (which & 1) {
						tox = Math.round(x1 + (x2 - x1) * ratio);
						toy = Math.round(y1 + (y2 - y1) * ratio);
					} else {
						tox = x2;
						toy = y2;
					}
					if (which & 2) {
						fromx = x1 + (x2 - x1) * (1 - ratio);
						fromy = y1 + (y2 - y1) * (1 - ratio);
					} else {
						fromx = x1;
						fromy = y1;
					}
					ctx.beginPath();
					ctx.moveTo(fromx, fromy);
					ctx.lineTo(tox, toy);
					ctx.stroke();
					var lineangle = Math.atan2(y2 - y1, x2 - x1);
					var h = Math.abs(d / Math.cos(angle));
					if (which & 1) {
						var angle1 = lineangle + Math.PI + angle;
						var topx = x2 + Math.cos(angle1) * h;
						var topy = y2 + Math.sin(angle1) * h;
						var angle2 = lineangle + Math.PI - angle;
						var botx = x2 + Math.cos(angle2) * h;
						var boty = y2 + Math.sin(angle2) * h;
						toDrawHead(ctx, topx, topy, x2, y2, botx, boty, style);
					}
					if (which & 2) {
						var angle1 = lineangle + angle;
						var topx = x1 + Math.cos(angle1) * h;
						var topy = y1 + Math.sin(angle1) * h;
						var angle2 = lineangle - angle;
						var botx = x1 + Math.cos(angle2) * h;
						var boty = y1 + Math.sin(angle2) * h;
						toDrawHead(ctx, topx, topy, x1, y1, botx, boty, style);
					}
				};

				var spinColors = function(currentIteration, totalIterations) {
					var step = currentIteration % totalIterations;
					if (step < oldStep)
						colors.push(colors.shift());
					var c0 = colors[0],
						c1 = colors[1],
						r = minmax(easeLinear(step, c0.r, c1.r - c0.r, totalIterations), c0.r, c1.r),
						g = minmax(easeLinear(step, c0.g, c1.g - c0.g, totalIterations), c0.g, c1.g),
						b = minmax(easeLinear(step, c0.b, c1.b - c0.b, totalIterations), c0.b, c1.b);

					oldStep = step;
					return "rgb(" + parseInt(r) + "," + parseInt(g) + "," + parseInt(b) + ")";
				}

				var spin = function(t) {
					var timeCurrent = t || (new Date).getTime();
					if (!startTime) {
						startTime = timeCurrent;
					}
					tick = timeCurrent - startTime;
					acc = easeInOutQuad((tick + tail_duration / 2) % tail_duration, 0, duration, tail_duration);
					head = easeLinear((tick + acc) % duration, 0, 360, duration);
					tail = 20 + Math.abs(easeLinear((tick + tail_duration / 2) % tail_duration, -300, 600, tail_duration));

					ctx.lineWidth = lineWidth;
					ctx.lineCap = "round";

					ctx.strokeStyle = spinColors(tick, duration);
					ctx.clearRect(0, 0, size, size);
					//fixed android 4.1.x
					canvasObj.style.display = 'none'; // Detach from DOM
					canvasObj.offsetHeight; // Force the detach
					canvasObj.style.display = 'inherit'; // Reattach to DOM
					ctx.beginPath();
					ctx.arc(size / 2, size / 2, size / 4, parseInt(head - tail) % 360 * rad, parseInt(head) % 360 * rad, false);
					ctx.stroke();

					rAF = requestAnimationFrame(spin);
				};
				var startSpin = function() {
					startTime = 0;
					oldStep = 0;
					rAF = requestAnimationFrame(spin);
				};
				var stopSpin = function() {
					rAF && cancelAnimationFrame(rAF);
				}
				var init = function(canvas, options) {
					canvasObj = canvas;
					ctx = canvasObj.getContext('2d');
					var options = $.extend(true, {}, options);
					colors = options.colors;
					duration = options.duration;
					tail_duration = options.tail_duration;
					size = options.size;
					lineWidth = options.lineWidth;
				};
				return {
					init: init,
					drawArcedArrow: drawArcedArrow,
					startSpin: startSpin,
					stopSpin: stopSpin
				};
			})()
		});
	})(mui);
});