'use strict';

function _regeneratorRuntime() {
  _regeneratorRuntime = function () {
    return exports;
  };
  var exports = {},
    Op = Object.prototype,
    hasOwn = Op.hasOwnProperty,
    defineProperty = Object.defineProperty || function (obj, key, desc) {
      obj[key] = desc.value;
    },
    $Symbol = "function" == typeof Symbol ? Symbol : {},
    iteratorSymbol = $Symbol.iterator || "@@iterator",
    asyncIteratorSymbol = $Symbol.asyncIterator || "@@asyncIterator",
    toStringTagSymbol = $Symbol.toStringTag || "@@toStringTag";
  function define(obj, key, value) {
    return Object.defineProperty(obj, key, {
      value: value,
      enumerable: !0,
      configurable: !0,
      writable: !0
    }), obj[key];
  }
  try {
    define({}, "");
  } catch (err) {
    define = function (obj, key, value) {
      return obj[key] = value;
    };
  }
  function wrap(innerFn, outerFn, self, tryLocsList) {
    var protoGenerator = outerFn && outerFn.prototype instanceof Generator ? outerFn : Generator,
      generator = Object.create(protoGenerator.prototype),
      context = new Context(tryLocsList || []);
    return defineProperty(generator, "_invoke", {
      value: makeInvokeMethod(innerFn, self, context)
    }), generator;
  }
  function tryCatch(fn, obj, arg) {
    try {
      return {
        type: "normal",
        arg: fn.call(obj, arg)
      };
    } catch (err) {
      return {
        type: "throw",
        arg: err
      };
    }
  }
  exports.wrap = wrap;
  var ContinueSentinel = {};
  function Generator() {}
  function GeneratorFunction() {}
  function GeneratorFunctionPrototype() {}
  var IteratorPrototype = {};
  define(IteratorPrototype, iteratorSymbol, function () {
    return this;
  });
  var getProto = Object.getPrototypeOf,
    NativeIteratorPrototype = getProto && getProto(getProto(values([])));
  NativeIteratorPrototype && NativeIteratorPrototype !== Op && hasOwn.call(NativeIteratorPrototype, iteratorSymbol) && (IteratorPrototype = NativeIteratorPrototype);
  var Gp = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(IteratorPrototype);
  function defineIteratorMethods(prototype) {
    ["next", "throw", "return"].forEach(function (method) {
      define(prototype, method, function (arg) {
        return this._invoke(method, arg);
      });
    });
  }
  function AsyncIterator(generator, PromiseImpl) {
    function invoke(method, arg, resolve, reject) {
      var record = tryCatch(generator[method], generator, arg);
      if ("throw" !== record.type) {
        var result = record.arg,
          value = result.value;
        return value && "object" == typeof value && hasOwn.call(value, "__await") ? PromiseImpl.resolve(value.__await).then(function (value) {
          invoke("next", value, resolve, reject);
        }, function (err) {
          invoke("throw", err, resolve, reject);
        }) : PromiseImpl.resolve(value).then(function (unwrapped) {
          result.value = unwrapped, resolve(result);
        }, function (error) {
          return invoke("throw", error, resolve, reject);
        });
      }
      reject(record.arg);
    }
    var previousPromise;
    defineProperty(this, "_invoke", {
      value: function (method, arg) {
        function callInvokeWithMethodAndArg() {
          return new PromiseImpl(function (resolve, reject) {
            invoke(method, arg, resolve, reject);
          });
        }
        return previousPromise = previousPromise ? previousPromise.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg();
      }
    });
  }
  function makeInvokeMethod(innerFn, self, context) {
    var state = "suspendedStart";
    return function (method, arg) {
      if ("executing" === state) throw new Error("Generator is already running");
      if ("completed" === state) {
        if ("throw" === method) throw arg;
        return doneResult();
      }
      for (context.method = method, context.arg = arg;;) {
        var delegate = context.delegate;
        if (delegate) {
          var delegateResult = maybeInvokeDelegate(delegate, context);
          if (delegateResult) {
            if (delegateResult === ContinueSentinel) continue;
            return delegateResult;
          }
        }
        if ("next" === context.method) context.sent = context._sent = context.arg;else if ("throw" === context.method) {
          if ("suspendedStart" === state) throw state = "completed", context.arg;
          context.dispatchException(context.arg);
        } else "return" === context.method && context.abrupt("return", context.arg);
        state = "executing";
        var record = tryCatch(innerFn, self, context);
        if ("normal" === record.type) {
          if (state = context.done ? "completed" : "suspendedYield", record.arg === ContinueSentinel) continue;
          return {
            value: record.arg,
            done: context.done
          };
        }
        "throw" === record.type && (state = "completed", context.method = "throw", context.arg = record.arg);
      }
    };
  }
  function maybeInvokeDelegate(delegate, context) {
    var methodName = context.method,
      method = delegate.iterator[methodName];
    if (undefined === method) return context.delegate = null, "throw" === methodName && delegate.iterator.return && (context.method = "return", context.arg = undefined, maybeInvokeDelegate(delegate, context), "throw" === context.method) || "return" !== methodName && (context.method = "throw", context.arg = new TypeError("The iterator does not provide a '" + methodName + "' method")), ContinueSentinel;
    var record = tryCatch(method, delegate.iterator, context.arg);
    if ("throw" === record.type) return context.method = "throw", context.arg = record.arg, context.delegate = null, ContinueSentinel;
    var info = record.arg;
    return info ? info.done ? (context[delegate.resultName] = info.value, context.next = delegate.nextLoc, "return" !== context.method && (context.method = "next", context.arg = undefined), context.delegate = null, ContinueSentinel) : info : (context.method = "throw", context.arg = new TypeError("iterator result is not an object"), context.delegate = null, ContinueSentinel);
  }
  function pushTryEntry(locs) {
    var entry = {
      tryLoc: locs[0]
    };
    1 in locs && (entry.catchLoc = locs[1]), 2 in locs && (entry.finallyLoc = locs[2], entry.afterLoc = locs[3]), this.tryEntries.push(entry);
  }
  function resetTryEntry(entry) {
    var record = entry.completion || {};
    record.type = "normal", delete record.arg, entry.completion = record;
  }
  function Context(tryLocsList) {
    this.tryEntries = [{
      tryLoc: "root"
    }], tryLocsList.forEach(pushTryEntry, this), this.reset(!0);
  }
  function values(iterable) {
    if (iterable) {
      var iteratorMethod = iterable[iteratorSymbol];
      if (iteratorMethod) return iteratorMethod.call(iterable);
      if ("function" == typeof iterable.next) return iterable;
      if (!isNaN(iterable.length)) {
        var i = -1,
          next = function next() {
            for (; ++i < iterable.length;) if (hasOwn.call(iterable, i)) return next.value = iterable[i], next.done = !1, next;
            return next.value = undefined, next.done = !0, next;
          };
        return next.next = next;
      }
    }
    return {
      next: doneResult
    };
  }
  function doneResult() {
    return {
      value: undefined,
      done: !0
    };
  }
  return GeneratorFunction.prototype = GeneratorFunctionPrototype, defineProperty(Gp, "constructor", {
    value: GeneratorFunctionPrototype,
    configurable: !0
  }), defineProperty(GeneratorFunctionPrototype, "constructor", {
    value: GeneratorFunction,
    configurable: !0
  }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, toStringTagSymbol, "GeneratorFunction"), exports.isGeneratorFunction = function (genFun) {
    var ctor = "function" == typeof genFun && genFun.constructor;
    return !!ctor && (ctor === GeneratorFunction || "GeneratorFunction" === (ctor.displayName || ctor.name));
  }, exports.mark = function (genFun) {
    return Object.setPrototypeOf ? Object.setPrototypeOf(genFun, GeneratorFunctionPrototype) : (genFun.__proto__ = GeneratorFunctionPrototype, define(genFun, toStringTagSymbol, "GeneratorFunction")), genFun.prototype = Object.create(Gp), genFun;
  }, exports.awrap = function (arg) {
    return {
      __await: arg
    };
  }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, asyncIteratorSymbol, function () {
    return this;
  }), exports.AsyncIterator = AsyncIterator, exports.async = function (innerFn, outerFn, self, tryLocsList, PromiseImpl) {
    void 0 === PromiseImpl && (PromiseImpl = Promise);
    var iter = new AsyncIterator(wrap(innerFn, outerFn, self, tryLocsList), PromiseImpl);
    return exports.isGeneratorFunction(outerFn) ? iter : iter.next().then(function (result) {
      return result.done ? result.value : iter.next();
    });
  }, defineIteratorMethods(Gp), define(Gp, toStringTagSymbol, "Generator"), define(Gp, iteratorSymbol, function () {
    return this;
  }), define(Gp, "toString", function () {
    return "[object Generator]";
  }), exports.keys = function (val) {
    var object = Object(val),
      keys = [];
    for (var key in object) keys.push(key);
    return keys.reverse(), function next() {
      for (; keys.length;) {
        var key = keys.pop();
        if (key in object) return next.value = key, next.done = !1, next;
      }
      return next.done = !0, next;
    };
  }, exports.values = values, Context.prototype = {
    constructor: Context,
    reset: function (skipTempReset) {
      if (this.prev = 0, this.next = 0, this.sent = this._sent = undefined, this.done = !1, this.delegate = null, this.method = "next", this.arg = undefined, this.tryEntries.forEach(resetTryEntry), !skipTempReset) for (var name in this) "t" === name.charAt(0) && hasOwn.call(this, name) && !isNaN(+name.slice(1)) && (this[name] = undefined);
    },
    stop: function () {
      this.done = !0;
      var rootRecord = this.tryEntries[0].completion;
      if ("throw" === rootRecord.type) throw rootRecord.arg;
      return this.rval;
    },
    dispatchException: function (exception) {
      if (this.done) throw exception;
      var context = this;
      function handle(loc, caught) {
        return record.type = "throw", record.arg = exception, context.next = loc, caught && (context.method = "next", context.arg = undefined), !!caught;
      }
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i],
          record = entry.completion;
        if ("root" === entry.tryLoc) return handle("end");
        if (entry.tryLoc <= this.prev) {
          var hasCatch = hasOwn.call(entry, "catchLoc"),
            hasFinally = hasOwn.call(entry, "finallyLoc");
          if (hasCatch && hasFinally) {
            if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0);
            if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc);
          } else if (hasCatch) {
            if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0);
          } else {
            if (!hasFinally) throw new Error("try statement without catch or finally");
            if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc);
          }
        }
      }
    },
    abrupt: function (type, arg) {
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        if (entry.tryLoc <= this.prev && hasOwn.call(entry, "finallyLoc") && this.prev < entry.finallyLoc) {
          var finallyEntry = entry;
          break;
        }
      }
      finallyEntry && ("break" === type || "continue" === type) && finallyEntry.tryLoc <= arg && arg <= finallyEntry.finallyLoc && (finallyEntry = null);
      var record = finallyEntry ? finallyEntry.completion : {};
      return record.type = type, record.arg = arg, finallyEntry ? (this.method = "next", this.next = finallyEntry.finallyLoc, ContinueSentinel) : this.complete(record);
    },
    complete: function (record, afterLoc) {
      if ("throw" === record.type) throw record.arg;
      return "break" === record.type || "continue" === record.type ? this.next = record.arg : "return" === record.type ? (this.rval = this.arg = record.arg, this.method = "return", this.next = "end") : "normal" === record.type && afterLoc && (this.next = afterLoc), ContinueSentinel;
    },
    finish: function (finallyLoc) {
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        if (entry.finallyLoc === finallyLoc) return this.complete(entry.completion, entry.afterLoc), resetTryEntry(entry), ContinueSentinel;
      }
    },
    catch: function (tryLoc) {
      for (var i = this.tryEntries.length - 1; i >= 0; --i) {
        var entry = this.tryEntries[i];
        if (entry.tryLoc === tryLoc) {
          var record = entry.completion;
          if ("throw" === record.type) {
            var thrown = record.arg;
            resetTryEntry(entry);
          }
          return thrown;
        }
      }
      throw new Error("illegal catch attempt");
    },
    delegateYield: function (iterable, resultName, nextLoc) {
      return this.delegate = {
        iterator: values(iterable),
        resultName: resultName,
        nextLoc: nextLoc
      }, "next" === this.method && (this.arg = undefined), ContinueSentinel;
    }
  }, exports;
}
function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) {
  try {
    var info = gen[key](arg);
    var value = info.value;
  } catch (error) {
    reject(error);
    return;
  }
  if (info.done) {
    resolve(value);
  } else {
    Promise.resolve(value).then(_next, _throw);
  }
}
function _asyncToGenerator(fn) {
  return function () {
    var self = this,
      args = arguments;
    return new Promise(function (resolve, reject) {
      var gen = fn.apply(self, args);
      function _next(value) {
        asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value);
      }
      function _throw(err) {
        asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err);
      }
      _next(undefined);
    });
  };
}
function _extends() {
  _extends = Object.assign ? Object.assign.bind() : function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];
      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }
    return target;
  };
  return _extends.apply(this, arguments);
}
function _unsupportedIterableToArray(o, minLen) {
  if (!o) return;
  if (typeof o === "string") return _arrayLikeToArray(o, minLen);
  var n = Object.prototype.toString.call(o).slice(8, -1);
  if (n === "Object" && o.constructor) n = o.constructor.name;
  if (n === "Map" || n === "Set") return Array.from(o);
  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen);
}
function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;
  for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i];
  return arr2;
}
function _createForOfIteratorHelperLoose(o, allowArrayLike) {
  var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"];
  if (it) return (it = it.call(o)).next.bind(it);
  if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") {
    if (it) o = it;
    var i = 0;
    return function () {
      if (i >= o.length) return {
        done: true
      };
      return {
        done: false,
        value: o[i++]
      };
    };
  }
  throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

var UINodeType;
(function (UINodeType) {
  UINodeType[UINodeType["NULL"] = -1] = "NULL";
  UINodeType[UINodeType["STATIC"] = 0] = "STATIC";
  UINodeType[UINodeType["DYNAMIC"] = 1] = "DYNAMIC";
})(UINodeType || (UINodeType = {}));

var error = function error(err, expression, el) {
  var message = "LeafUI Error: \"" + err + "\"";
  if (expression) message += "\n\nExpression: \"" + expression + "\"";
  if (el) message += "\nElement:";
  console.warn(message, el);
};

var Connection = /*#__PURE__*/function () {
  function Connection() {}
  Connection.connect = function connect(type, uiData, dom) {
    var _component$getAttribu;
    var pageState = {};
    var component = uiData.element.closest('[ui-state]');
    var componentData = JSON.parse((_component$getAttribu = component == null ? void 0 : component.getAttribute('ui-state')) != null ? _component$getAttribu : '{}');
    var components = document.querySelectorAll('[ui-state]');
    components.forEach(function (i) {
      var _i$getAttribute;
      var attr = JSON.parse((_i$getAttribute = i.getAttribute('ui-state')) != null ? _i$getAttribute : '{}');
      pageState[attr.key] = attr;
    });
    var payload = {
      type: type,
      payload: {
        params: [],
        method: uiData.method,
        methodArgs: uiData.methodArgs,
        component: componentData == null ? void 0 : componentData.key,
        data: pageState
      }
    };
    return fetch(window.location.href + "?_leaf_ui_config=" + JSON.stringify(payload), {
      method: uiData.config.method,
      // This enables "cookies".
      credentials: 'same-origin',
      headers: _extends({
        'Content-Type': 'application/json',
        Accept: 'text/html, application/xhtml+xml',
        'X-Leaf-UI': 'true'
      }, this.headers, {
        // We'll set this explicitly to mitigate potential interference from ad-blockers/etc.
        Referer: window.location.href
      })
    }).then( /*#__PURE__*/function () {
      var _ref = _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee(response) {
        return _regeneratorRuntime().wrap(function _callee$(_context) {
          while (1) switch (_context.prev = _context.next) {
            case 0:
              if (!response.ok) {
                _context.next = 4;
                break;
              }
              response.text().then(function (response) {
                var data = JSON.parse(response);
                window._leafUIConfig.data = data.state;
                dom.diff(data.html, component.nodeName === 'HTML' || !component ? document.body : component);
              });
              _context.next = 9;
              break;
            case 4:
              _context.t0 = error;
              _context.next = 7;
              return response.text().then(function (res) {
                return res;
              });
            case 7:
              _context.t1 = _context.sent;
              (0, _context.t0)(_context.t1);
            case 9:
            case "end":
              return _context.stop();
          }
        }, _callee);
      }));
      return function (_x) {
        return _ref.apply(this, arguments);
      };
    }());
  };
  return Connection;
}();

var rawDirectiveSplitRE = function rawDirectiveSplitRE() {
  return /:|\./gim;
};
var DIRECTIVE_SHORTHANDS;
(function (DIRECTIVE_SHORTHANDS) {
  DIRECTIVE_SHORTHANDS["@"] = "on";
  DIRECTIVE_SHORTHANDS[":"] = "bind";
})(DIRECTIVE_SHORTHANDS || (DIRECTIVE_SHORTHANDS = {}));
function arraysMatch(a, b) {
  return Array.isArray(a) && Array.isArray(b) && a.length === b.length && a.every(function (val, index) {
    return val === b[index];
  });
}
window.leafUI = window.leafUI || {};

var Dom = /*#__PURE__*/function () {
  function Dom() {}
  /**
   * Get the body of an HTML string
   *
   * @param html The html to parse
   * @param removeScripts Whether to remove scripts from the html
   * @returns The body/root of the html
   */
  Dom.getBody = function getBody(html, removeScripts, nodeToReturn) {
    if (removeScripts === void 0) {
      removeScripts = false;
    }
    if (nodeToReturn === void 0) {
      nodeToReturn = 'body';
    }
    var parser = new DOMParser();
    var dom = parser.parseFromString(html, 'text/html');
    if (removeScripts === true) {
      var scripts = dom.body.getElementsByTagName('script');
      for (var i = 0; i < scripts.length; i++) {
        scripts[i].remove();
      }
    }
    return nodeToReturn === 'body' ? dom.body : dom.documentElement;
  }
  /**
   * Wrap DOM node with a template element
   */;
  Dom.wrap = function wrap(node) {
    var wrapper = document.createElement('x-leafui-wrapper');
    wrapper.appendChild(node);
    return wrapper;
  }
  /**
   * Parse string to DOM
   *
   * @param html The html to parse
   */;
  Dom.parse = function parse(html) {
    var parser = new DOMParser();
    var dom = parser.parseFromString(html, 'text/html');
    return dom.getRootNode().firstChild;
  }
  /**
   * Get the type for a node
   * @param  {HTMLElement} node The node
   * @return {String} The type
   */;
  Dom.getNodeType = function getNodeType(node) {
    if (node.nodeType === 3) return 'text';
    if (node.nodeType === 8) return 'comment';
    return node.tagName.toLowerCase();
  }
  /**
   * Get the content from a node
   * @param  {Node}   node The node
   * @return {String}      The type
   */;
  Dom.getNodeContent = function getNodeContent(node) {
    if (node.children && node.children.length > 0) return null;
    return node.textContent;
  }
  /**
   * Diff the DOM from a string and an element
   *
   * @param newNode The new node
   * @param oldNode The old node
   * @returns The diffed node
   */;
  Dom.diff = function diff(newNode, oldNode) {
    if (newNode.includes('<html')) {
      if (typeof window !== 'undefined') {
        oldNode = window.document.documentElement;
      }
    }
    var structuredNewNode = oldNode instanceof HTMLHtmlElement ? Dom.getBody(newNode, false, 'root') : oldNode.nodeName === 'BODY' ? Dom.getBody(newNode, false) : Dom.getBody(newNode, true).children[0];
    var structuredOldNode = oldNode;
    Dom.diffElements(structuredNewNode, structuredOldNode);
  }
  /**
   * Diff the DOM from two elements
   *
   * @param newNode The new node
   * @param oldNode The old node
   * @returns The diffed node
   */;
  Dom.diffElements = function diffElements(newNode, oldNode) {
    var newNodes = Array.prototype.slice.call(newNode.children);
    var oldNodes = Array.prototype.slice.call(oldNode.children);
    var count = oldNodes.length - newNodes.length;
    if (count > 0) {
      for (; count > 0; count--) {
        oldNodes[oldNodes.length - count].parentNode.removeChild(oldNodes[oldNodes.length - count]);
      }
    }
    for (var index = 0; index < newNodes.length; index++) {
      var _node$parentNode$attr, _node$parentNode, _oldNodes$index$paren, _oldNodes$index$paren2, _Object$keys, _oldNodes$index2, _oldNodes$index3;
      var node = newNodes[index];
      if (!oldNodes[index]) {
        var newNodeClone = node.cloneNode(true);
        oldNode.appendChild(newNodeClone);
        initComponent(newNodeClone);
        continue;
      }
      if (node instanceof HTMLScriptElement && oldNodes[index] instanceof HTMLScriptElement) {
        if (node.src !== oldNodes[index].src || node.innerHTML !== oldNodes[index].innerHTML) {
          var _newNodeClone = node.cloneNode(true);
          oldNodes[index].parentNode.replaceChild(_newNodeClone, oldNodes[index]);
        }
        continue;
      }
      if (!arraysMatch(Object.values((_node$parentNode$attr = (_node$parentNode = node.parentNode) == null ? void 0 : _node$parentNode.attributes) != null ? _node$parentNode$attr : {}), Object.values((_oldNodes$index$paren = (_oldNodes$index$paren2 = oldNodes[index].parentNode) == null ? void 0 : _oldNodes$index$paren2.attributes) != null ? _oldNodes$index$paren : {}))) {
        for (var nIndex = 0; nIndex < ((_node$parentNode$attr2 = node.parentNode.attributes) == null ? void 0 : _node$parentNode$attr2.length); nIndex++) {
          var _node$parentNode$attr2, _oldNodes$index, _oldNodes$index$paren3;
          var attribute = node.parentNode.attributes[nIndex];
          (_oldNodes$index = oldNodes[index]) == null ? void 0 : (_oldNodes$index$paren3 = _oldNodes$index.parentNode) == null ? void 0 : _oldNodes$index$paren3.setAttribute(attribute.name, attribute.value);
        }
      }
      if (Dom.getNodeType(node) !== Dom.getNodeType(oldNodes[index]) || !arraysMatch((_Object$keys = Object.keys((_oldNodes$index2 = oldNodes[index]) == null ? void 0 : _oldNodes$index2.attributes)) != null ? _Object$keys : [], Object.keys(node.attributes)) || ((_oldNodes$index3 = oldNodes[index]) == null ? void 0 : _oldNodes$index3.innerHTML) !== node.innerHTML) {
        var _newNodeClone2 = node.cloneNode(true);
        if (!oldNodes[index].parentNode) {
          oldNodes[index].replaceWith(_newNodeClone2);
          initComponent(_newNodeClone2);
        } else {
          oldNodes[index].parentNode.replaceChild(_newNodeClone2, oldNodes[index]);
          initComponent(_newNodeClone2);
        }
        continue;
      }
      // If content is different, update it
      var templateContent = Dom.getNodeContent(node);
      if (templateContent && templateContent !== Dom.getNodeContent(oldNodes[index])) {
        oldNodes[index].textContent = templateContent;
      }
      if (oldNodes[index].children.length > 0 && node.children.length < 1) {
        oldNodes[index].innerHTML = '';
        continue;
      }
      if (oldNodes[index].children.length < 1 && node.children.length > 0) {
        var fragment = document.createDocumentFragment();
        Dom.diffElements(node, fragment);
        oldNodes[index].appendChild(fragment);
        continue;
      }
      if (node.children.length > 0) {
        Dom.diffElements(node, oldNodes[index]);
      }
    }
  };
  return Dom;
}();

var compute = function compute(expression, el, refs) {
  if (refs === void 0) {
    refs = {};
  }
  var specialPropertiesNames = ['$el', '$emit', '$event', '$refs', '$dom'];
  // This "revives" a function from a string, only using the new Function syntax once during compilation.
  // This is because raw function is ~50,000x faster than new Function
  var computeFunction = new Function("return (" + specialPropertiesNames.join(',') + ") => {\n            const method = " + JSON.stringify(expression) + ".split('(')[0];\n            const methodArgs = " + JSON.stringify(expression) + ".substring(" + JSON.stringify(expression) + ".indexOf('(') + 1, " + JSON.stringify(expression) + ".lastIndexOf(')'));\n\n            if (!window._leafUIConfig.methods.includes(method)) {\n                return error(new ReferenceError(method + ' is not defined'), method, $el);\n            }\n\n            (" + Connection.connect + ")('callMethod', { element: $el, method, methodArgs, config: window._leafUIConfig }, $dom);\n        }")();
  var emit = function emit(name, options, dispatchGlobal) {
    if (dispatchGlobal === void 0) {
      dispatchGlobal = true;
    }
    var event = new CustomEvent(name, options);
    var target = dispatchGlobal ? window : el || window;
    target.dispatchEvent(event);
  };
  return function (event) {
    try {
      return computeFunction(el, emit, event, refs, Dom);
    } catch (err) {
      error(err, expression, el);
    }
  };
};

var flattenElementChildren = function flattenElementChildren(rootElement, ignoreRootElement) {
  if (ignoreRootElement === void 0) {
    ignoreRootElement = false;
  }
  var collection = [];
  if (!ignoreRootElement) {
    collection.push(rootElement);
  }
  for (var _iterator = _createForOfIteratorHelperLoose(rootElement.children), _step; !(_step = _iterator()).done;) {
    var childElement = _step.value;
    if (childElement instanceof HTMLElement) {
      collection.push.apply(collection, flattenElementChildren(childElement, childElement.attributes.length === 0));
    }
  }
  return collection;
};
var collectRefs = function collectRefs(element) {
  if (element === void 0) {
    element = document;
  }
  var refDirective = 'ui-ref';
  var refElements = element.querySelectorAll("[" + refDirective + "]");
  var refs = {};
  refElements.forEach(function (refElement) {
    var name = refElement.getAttribute(refDirective);
    if (name) {
      refs[name] = refElement;
    }
  });
  return refs;
};
var initDirectives = function initDirectives(el) {
  var directives = {};
  var refs = collectRefs();
  // @ts-ignore
  var _loop = function _loop() {
    var _step2$value = _step2.value,
      name = _step2$value.name,
      value = _step2$value.value;
    var hasDirectivePrefix = name.startsWith('ui-');
    var hasDirectiveShorthandPrefix = Object.keys(DIRECTIVE_SHORTHANDS).some(function (shorthand) {
      return name.startsWith(shorthand);
    });
    if (!(hasDirectivePrefix || hasDirectiveShorthandPrefix)) {
      return "continue";
    }
    var directiveData = {
      compute: compute(value, el, refs),
      value: value
    };
    // Handle normal and shorthand directives=
    var directiveName = hasDirectivePrefix ? name.slice('ui-'.length) : // @ts-ignore
    DIRECTIVE_SHORTHANDS[name[0]] + ":" + name.slice(1);
    directives[directiveName.toLowerCase()] = directiveData;
  };
  for (var _iterator2 = _createForOfIteratorHelperLoose(el.attributes), _step2; !(_step2 = _iterator2()).done;) {
    var _ret = _loop();
    if (_ret === "continue") continue;
  }
  return directives;
};
var createASTNode = function createASTNode(el) {
  var directives = initDirectives(el);
  var hasDirectives = Object.keys(directives).length > 0;
  var node = {
    el: el,
    directives: directives,
    type: UINodeType.STATIC
  };
  return hasDirectives ? node : undefined;
};
var compile = function compile(el, ignoreRootElement) {
  if (ignoreRootElement === void 0) {
    ignoreRootElement = false;
  }
  var uiNodes = [];
  var elements = flattenElementChildren(el, ignoreRootElement);
  elements.forEach(function (element) {
    var newASTNode = createASTNode(element);
    if (newASTNode) {
      uiNodes.push(newASTNode);
    }
  });
  return uiNodes;
};

/**
 * @author Aiden Bai <hello@aidenybai.com>
 * @package lucia
 */
// Lazy allows us to delay render calls if the main thread is blocked
// This is kind of like time slicing in React but less advanced
// It's a generator function that yields after a certain amount of time
// This allows the browser to render other things while the generator is running
// It's a bit like a setTimeout but it's more accurate
var lazy = function lazy(threshold, generatorFunction) {
  var generator = generatorFunction();
  return function next() {
    var start = performance.now();
    var task = null;
    do {
      task = generator.next();
    } while (performance.now() - start < threshold && !task.done);
    if (task.done) return;
    setTimeout(next);
  };
};

var onDirective = function onDirective(_ref) {
  var el = _ref.el,
    parts = _ref.parts,
    data = _ref.data;
  var options = {};
  var globalScopeEventProps = ['outside', 'global'];
  var eventProps = parts.slice(2);
  var EVENT_REGISTERED_FLAG = "__on_" + parts[1] + "_registered";
  // @ts-expect-error: We're adding a custom property to the element
  if (el[EVENT_REGISTERED_FLAG]) return;
  var target = globalScopeEventProps.some(function (prop) {
    return String(eventProps).includes(prop);
  }) ? window : el;
  var handler = function handler(event) {
    if (eventProps.length > 0) {
      if (event instanceof KeyboardEvent && /\d/gim.test(String(eventProps))) {
        var whitelistedKeycodes = [];
        eventProps.forEach(function (eventProp) {
          // @ts-expect-error: eventProp can be a string, but isNaN only accepts number
          if (!isNaN(eventProp)) {
            whitelistedKeycodes.push(Number(eventProp));
          }
        });
        if (!whitelistedKeycodes.includes(event.keyCode)) return;
      }
      // Parse event modifiers based on directive prop
      if (eventProps.includes('prevent')) event.preventDefault();
      if (eventProps.includes('stop')) event.stopPropagation();
      if (eventProps.includes('self')) {
        if (event.target !== el) return;
      }
      /* istanbul ignore next */
      if (eventProps.includes('outside')) {
        if (el.contains(event.target)) return;
        if (el.offsetWidth < 1 && el.offsetHeight < 1) return;
      }
      if (eventProps.includes('enter') || eventProps.includes('meta')) {
        if (event.key === 'Enter') {
          data.compute(event);
        }
      }
      if (eventProps.includes('ctrl') && event.ctrlKey || eventProps.includes('alt') && event.altKey || eventProps.includes('shift') && event.shiftKey || eventProps.includes('left') && 'button' in event && event.button === 0 || eventProps.includes('middle') && 'button' in event && event.button === 1 || eventProps.includes('right') && 'button' in event && event.button === 2) {
        data.compute(event);
      }
    } else {
      data.compute(event);
    }
  };
  options.once = eventProps.includes('once');
  options.passive = eventProps.includes('passive');
  target.addEventListener(parts[1], handler, options);
  // @ts-expect-error: We're adding a custom property to the element
  el[EVENT_REGISTERED_FLAG] = true;
};

// import { bindDirective } from './directives/bind';
var directives = {
  // BIND: bindDirective,
  // MODEL: modelDirective,
  ON: onDirective
};
var renderDirective = function renderDirective(props, directives) {
  directives[props.parts[0].toUpperCase()](props);
};

var render = function render(uiNodes, directives) {
  var legalDirectiveNames = Object.keys(directives);
  var LAZY_MODE_TIMEOUT = 25;
  lazy(LAZY_MODE_TIMEOUT, /*#__PURE__*/_regeneratorRuntime().mark(function _callee() {
    var _iterator, _step, node, isStatic, _i, _Object$entries, _Object$entries$_i, directiveName, directiveData, rawDirectiveName, directiveProps;
    return _regeneratorRuntime().wrap(function _callee$(_context) {
      while (1) switch (_context.prev = _context.next) {
        case 0:
          _iterator = _createForOfIteratorHelperLoose(uiNodes);
        case 1:
          if ((_step = _iterator()).done) {
            _context.next = 25;
            break;
          }
          node = _step.value;
          if (!(node.type === UINodeType.NULL)) {
            _context.next = 5;
            break;
          }
          return _context.abrupt("continue", 23);
        case 5:
          isStatic = node.type === UINodeType.STATIC;
          if (isStatic) node.type = UINodeType.NULL;
          _context.next = 9;
          return;
        case 9:
          if (isStatic) {
            _context.next = 11;
            break;
          }
          return _context.abrupt("continue", 23);
        case 11:
          _i = 0, _Object$entries = Object.entries(node.directives);
        case 12:
          if (!(_i < _Object$entries.length)) {
            _context.next = 23;
            break;
          }
          _Object$entries$_i = _Object$entries[_i], directiveName = _Object$entries$_i[0], directiveData = _Object$entries$_i[1];
          rawDirectiveName = directiveName.split(rawDirectiveSplitRE())[0];
          if (legalDirectiveNames.includes(rawDirectiveName.toUpperCase())) {
            _context.next = 17;
            break;
          }
          return _context.abrupt("continue", 20);
        case 17:
          _context.next = 19;
          return;
        case 19:
          // If affected, then push to render queue
          if (isStatic) {
            directiveProps = {
              el: node.el,
              parts: directiveName.split(rawDirectiveSplitRE()),
              data: directiveData,
              node: node
            };
            renderDirective(directiveProps, directives);
            // [TODO] Remove this after testing
            delete node.directives[directiveName];
          }
        case 20:
          _i++;
          _context.next = 12;
          break;
        case 23:
          _context.next = 1;
          break;
        case 25:
        case "end":
          return _context.stop();
      }
    }, _callee);
  }))();
};

var Component = /*#__PURE__*/function () {
  function Component() {
    this.uiNodes = [];
    this.uiNodes = [];
  }
  var _proto = Component.prototype;
  _proto.mount = function mount(el) {
    var rootEl = el instanceof HTMLElement ? el : document.querySelector(el) || document.body;
    this.uiNodes = compile(rootEl);
    this.render();
    rootEl['component'] = this;
    window.leafUI = {
      rootEl: rootEl,
      component: this
    };
    return this;
  }
  /**
   * Force renders the DOM based on props
   * @param {string[]=} props - Array of root level properties in state
   * @returns {undefined}
   */;
  _proto.render = function render$1() {
    render(this.uiNodes, directives);
  };
  return Component;
}();
var initComponent = function initComponent(element) {
  return new Component().mount(element);
};

/**
 * Initialize Your Leaf UI root component
 * @param {HTMLElement|Document} element - Root element to find uninitialized components
 */
var init = function init(element) {
  if (element === void 0) {
    element = document;
  }
  var leafUI = new Component();
  var rootElement = element instanceof Document ? element.body : element;
  leafUI.mount(rootElement);
};

/**
 * @author Caleb Porzio
 * @package livewire/livewire
 */
function monkeyPatchDomSetAttributeToAllowAtSymbols() {
  // Because morphdom may add attributes to elements containing "@" symbols
  // like in the case of an Alpine `@click` directive, we have to patch
  // the standard Element.setAttribute method to allow this to work.
  var original = Element.prototype.setAttribute;
  var hostDiv = document.createElement('div');
  Element.prototype.setAttribute = function newSetAttribute(name, value) {
    if (!name.includes('@')) {
      return original.call(this, name, value);
    }
    hostDiv.innerHTML = "<span " + name + "=\"" + value + "\"></span>";
    var attr = hostDiv.firstElementChild.getAttributeNode(name);
    hostDiv.firstElementChild.removeAttributeNode(attr);
    this.setAttributeNode(attr);
  };
}

document.addEventListener('DOMContentLoaded', function () {
  monkeyPatchDomSetAttributeToAllowAtSymbols();
  init();
  document.querySelectorAll('[ui-lazy]').forEach(function (el) {
    el.removeAttribute('ui-lazy');
  });
});
//# sourceMappingURL=ui.cjs.development.js.map
