// Slidebars 0.8 - http://plugins.adchsm.me/slidebars/ Written by Adam Smith - http://www.adchsm.me/ Released under MIT License - http://plugins.adchsm.me/slidebars/license.txt
;
(function(a) {
    a.slidebars = function(b) {
        var v = a.extend({
            siteClose: true,
            disableOver: false,
            hideControlClasses: false
        }, b);
        var s = document.createElement("div").style,
            q = false,
            j = false;
        if (s.MozTransition === "" || s.WebkitTransition === "" || s.OTransition === "" || s.transition === "") {
            q = true
        }
        if (s.MozTransform === "" || s.WebkitTransform === "" || s.OTransform === "" || s.transform === "") {
            j = true
        }
        var p = navigator.userAgent,
            x = false;
        if (p.match(/Android/)) {
            x = parseFloat(p.slice(p.indexOf("Android") + 8));
            if (x < 3) {
                a("html").addClass("sb-android")
            }
        }
        if (!a("#sb-site").length) {
            a("body").children().wrapAll('<div id="sb-site" />')
        }
        var o = a("#sb-site");
        if (!o.parent().is("body")) {
            o.appendTo("body")
        }
        o.addClass("sb-slide");
        if (a(".sb-left").length) {
            var d = a(".sb-left"),
                e = false;
            if (!d.parent().is("body")) {
                d.appendTo("body")
            }
            if (x && x < 3) {
                d.addClass("sb-static")
            }
            if (d.hasClass("sb-width-custom")) {
                d.css("width", d.attr("data-sb-width"))
            }
        }
        if (a(".sb-right").length) {
            var g = a(".sb-right"),
                i = false;
            if (!g.parent().is("body")) {
                g.appendTo("body")
            }
            if (x && x < 3) {
                g.addClass("sb-static")
            }
            if (g.hasClass("sb-width-custom")) {
                g.css("width", g.attr("data-sb-width"))
            }
        }

        function r() {
            var y = a("html").css("height");
           // o.css("minHeight", y);
			try
			{
            if (d.hasClass("sb-static")) {
                d.css("minHeight", y)
            }
			}
			catch(err) {
			}
            if (g.hasClass("sb-static")) {
                g.css("minHeight", y)
            }
        }
        r();
        var u = a(".sb-toggle-left, .sb-toggle-right, .sb-open-left, .sb-open-right, .sb-close");

        function n() {
            var y = a(window).width();
            if (!v.disableOver || (typeof v.disableOver === "number" && v.disableOver >= y)) {
                this.init = true;
                a("html").addClass("sb-init");
                if (v.hideControlClasses) {
                    u.show()
                }
            } else {
                if (typeof v.disableOver === "number" && v.disableOver < y) {
                    this.init = false;
                    a("html").removeClass("sb-init");
                    if (v.hideControlClasses) {
                        u.hide()
                    }
                    if (e || i) {
                        k()
                    }
                }
            }
        }
        n();
        var t, l = a(".sb-slide");
        if (q && j) {
            t = "translate";
            if (x && x < 4.4) {
                t = "side"
            }
        } else {
            t = "jQuery"
        }
        a("html").addClass("sb-anim-type-" + t);

        function c(y, B, A) {
            if (t === "translate") {
                y.css({
                    transform: "translate(" + B + ")"
                })
            } else {
                if (t === "side") {
                    y.css(A, B)
                } else {
                    if (t === "jQuery") {
                        var z = {};
                        z[A] = B;
                        y.stop().animate(z, 400)
                    }
                }
            }
        }

        function h(y) {
            if (y === "left" && d && i || y === "right" && g && e) {
                k();
                setTimeout(z, 400)
            } else {
                z()
            }

            function z() {
                if (this.init && y === "left" && d) {
                    var A = d.css("width");
                    a("html").addClass("sb-active sb-active-left");
                    c(l, A, "left");
                    setTimeout(function() {
                        e = true
                    }, 400)
                } else {
                    if (this.init && y === "right" && g) {
                        var B = g.css("width");
                        a("html").addClass("sb-active sb-active-right");
                        c(l, "-" + B, "left");
                        setTimeout(function() {
                            i = true
                        }, 400)
                    }
                }
            }
        }

        function k(y) {
            if (e || i) {
                e = false;
                i = false;
                c(l, "0px", "left");
                setTimeout(function() {
                    a("html").removeClass("sb-active sb-active-left sb-active-right");
                    if (y) {
                        window.location = y
                    }
                }, 400)
            }
        }

        function m(y) {
            if (y === "left" && d) {
                if (e) {
                    k()
                } else {
                    if (!e) {
                        h("left")
                    }
                }
            } else {
                if (y === "right" && g) {
                    if (i) {
                        k()
                    } else {
                        if (!i) {
                            h("right")
                        }
                    }
                }
            }
        }
        this.open = h;
        this.close = k;
        this.toggle = m;

        function w() {
            r();
            n();
            if (e) {
                h("left")
            } else {
                if (i) {
                    h("right")
                }
            }
        }
        a(window).resize(w);

        function f(y) {
            y.preventDefault();
            y.stopPropagation()
        }
        a(".sb-toggle-left").on("touchend click", function(y) {
            f(y);
            m("left")
        });
        a(".sb-toggle-right").on("touchend click", function(y) {
            f(y);
            m("right")
        });
        a(".sb-open-left").on("touchend click", function(y) {
            f(y);
            if (!e) {
                h("left")
            }
        });
        a(".sb-open-right").on("touchend click", function(y) {
            f(y);
            if (!i) {
                h("right")
            }
        });
        a(".sb-close").on("touchend click", function(y) {
            f(y);
            if (e || i) {
                k()
            }
        });
        a(".sb-slidebar a").not(".sb-disable-close").on("click", function(y) {
            if (e || i) {
                f(y);
                k(a(this).attr("href"))
            }
        });
        o.on("touchend click", function(y) {
            if (e || i) {
                f(y);
                k()
            }
        })
    }
})(jQuery);