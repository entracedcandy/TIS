(function (a) {
	a.MaskedInput = function (f) {
		if (!f || !f.elm || !f.format) {
			return null
		}
		if (!(this instanceof a.MaskedInput)) {
			return new a.MaskedInput(f)
		}
		var n = this,
			d = f.elm,
			r = f.format,
			h = f.allowed || "0123456789",
			o = f.separator || "/:-",
			m = f.typeon || "_YMDhms",
			c = f.onbadkey || function () {},
			p = f.onfilled || function () {},
			v = f.badkeywait || 0,
			z = f.hasOwnProperty("preserve") ? !!f.preserve : true,
			k = true,
			w = false,
			s = r,
			i = (function () {
				if (window.addEventListener) {
					return function (D, B, C, A) {
						D.addEventListener(B, C, (A === undefined) ? false : A)
					}
				}
				if (window.attachEvent) {
					return function (C, A, B) {
						C.attachEvent("on" + A, B)
					}
				}
				return function (C, A, B) {
					C["on" + A] = B
				}
			}()),
			t = function () {
				for (var A = d.value.length - 1; A >= 0; A--) {
					for (var C = 0, B = m.length; C < B; C++) {
						if (d.value[A] === m[C]) {
							return false
						}
					}
				}
				return true
			},
			x = function (B) {
				try {
					B.focus();
					if (B.selectionStart >= 0) {
						return B.selectionStart
					}
					if (document.selection) {
						var A = document.selection.createRange();
						return -A.moveStart("character", -B.value.length)
					}
					return -1
				} catch (C) {
					return -1
				}
			},
			b = function (B, D) {
				try {
					if (B.selectionStart) {
						B.focus();
						B.setSelectionRange(D, D)
					} else {
						if (B.createTextRange) {
							var A = B.createTextRange();
							A.move("character", D);
							A.select()
						}
					}
				} catch (C) {
					return false
				}
				return true
			},
			l = function (C) {
				C = C || window.event;
				var B = "",
					D = C.which,
					A = C.type;
				if (D === undefined || D === null) {
					D = C.keyCode
				}
				if (D === undefined || D === null) {
					return ""
				}
				switch (D) {
					case 8:
						B = "bksp";
						break;
					case 46:
						B = (A === "keydown") ? "del" : ".";
						break;
					case 16:
						B = "shift";
						break;
					case 0:
					case 9:
					case 13:
						B = "etc";
						break;
					case 37:
					case 38:
					case 39:
					case 40:
						B = (!C.shiftKey && (C.charCode !== 39 && C.charCode !== undefined)) ? "etc" : String.fromCharCode(D);
						break;
					default:
						B = String.fromCharCode(D);
						break
				}
				return B
			},
			u = function (A, B) {
				if (A.preventDefault) {
					A.preventDefault()
				}
				A.returnValue = B || false
			},
			j = function (A) {
				var C = x(d),
					E = d.value,
					D = "",
					B = true;
				switch (B) {
					case (h.indexOf(A) !== -1):
						C = C + 1;
						if (C > r.length) {
							return false
						}
						while (o.indexOf(E.charAt(C - 1)) !== -1 && C <= r.length) {
							C = C + 1
						}
						D = E.substr(0, C - 1) + A + E.substr(C);
						if (h.indexOf(E.charAt(C)) === -1 && m.indexOf(E.charAt(C)) === -1) {
							C = C + 1
						}
						break;
					case (A === "bksp"):
						C = C - 1;
						if (C < 0) {
							return false
						}
						while (h.indexOf(E.charAt(C)) === -1 && m.indexOf(E.charAt(C)) === -1 && C > 1) {
							C = C - 1
						}
						D = E.substr(0, C) + r.substr(C, 1) + E.substr(C + 1);
						break;
					case (A === "del"):
						if (C >= E.length) {
							return false
						}
						while (o.indexOf(E.charAt(C)) !== -1 && E.charAt(C) !== "") {
							C = C + 1
						}
						D = E.substr(0, C) + r.substr(C, 1) + E.substr(C + 1);
						C = C + 1;
						break;
					case (A === "etc"):
						return true;
					default:
						return false
				}
				d.value = "";
				d.value = D;
				b(d, C);
				return false
			},
			g = function (A) {
				if (h.indexOf(A) === -1 && A !== "bksp" && A !== "del" && A !== "etc") {
					var B = x(d);
					w = true;
					c(A);
					setTimeout(function () {
						w = false;
						b(d, B)
					}, v);
					return false
				}
				return true
			},
			y = function (B) {
				if (!k) {
					return true
				}
				if (w) {
					u(B);
					return false
				}
				B = B || event;
				var A = l(B);
				if ((B.metaKey || B.ctrlKey) && (A === "X" || A === "V")) {
					u(B);
					return false
				}
				if (B.metaKey || B.ctrlKey) {
					return true
				}
				if (d.value === "") {
					d.value = r;
					b(d, 0)
				}
				if (A === "bksp" || A === "del") {
					j(A);
					u(B);
					return false
				}
				return true
			},
			e = function (B) {
				if (!k) {
					return true
				}
				if (w) {
					u(B);
					return false
				}
				B = B || event;
				var A = l(B);
				if (A === "etc" || B.metaKey || B.ctrlKey || B.altKey) {
					return true
				}
				if (A !== "bksp" && A !== "del" && A !== "shift") {
					if (!g(A)) {
						u(B);
						return false
					}
					if (j(A)) {
						if (t()) {
							p()
						}
						u(B, true);
						return true
					}
					if (t()) {
						p()
					}
					u(B);
					return false
				}
				return false
			},
			q = function () {
				if (!d.tagName || (d.tagName.toUpperCase() !== "INPUT" && d.tagName.toUpperCase() !== "TEXTAREA")) {
					return null
				}
				if (!z || d.value === "") {
					d.value = r
				}
				i(d, "keydown", function (A) {
					y(A)
				});
				i(d, "keypress", function (A) {
					e(A)
				});
				i(d, "focus", function () {
					s = d.value
				});
				i(d, "blur", function () {
					if (d.value !== s && d.onchange) {
						d.onchange()
					}
				});
				return n
			};
		n.resetField = function () {
			d.value = r
		};
		n.setAllowed = function (A) {
			h = A;
			n.resetField()
		};
		n.setFormat = function (A) {
			r = A;
			n.resetField()
		};
		n.setSeparator = function (A) {
			o = A;
			n.resetField()
		};
		n.setTypeon = function (A) {
			m = A;
			n.resetField()
		};
		n.setEnabled = function (A) {
			k = A
		};
		return q()
	}
}(window));