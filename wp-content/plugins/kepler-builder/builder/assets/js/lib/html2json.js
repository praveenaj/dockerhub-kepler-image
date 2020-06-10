(function (global) {
  const DEBUG = false;
  var debug = DEBUG ? console.log.bind(console) : function () {};

  if (typeof module === 'object' && typeof module.exports === 'object') {
    require('../lib/Pure-JavaScript-HTML5-Parser/htmlparser.js');
  }

  function q(v) {
    return '"' + v + '"';
  }

  function removeDOCTYPE(html) {
    return html
      .replace(/<\?xml.*\?>\n/, '')
      .replace(/<!doctype.*\>\n/, '')
      .replace(/<br>\n/, '')
      .replace(/<!DOCTYPE.*\>\n/, '');
  }

  function generateUID() {
    var genID = ("0000" + (Math.random() * Math.pow(36, 4) << 0).toString(36)).slice(-4);
    genID = 'generic_' + genID.replace("+", "");
    
    return (genID);
  }

  global.html2json = function html2json(html) {
    html = removeDOCTYPE(html).trim();
    var bufArray = [];
    var results = {
      node: 'root',
      nodes: [],
    };
    HTMLParser(html, {
      start: function (tag, attrs, unary) {
        debug(tag, attrs, unary);
        // node for this element
        var node = {
          node: 'element',
          tag: tag,
          element: tag.replace('kp_', '')
        };
        if (attrs.length !== 0) {
          node.props = attrs.reduce(function (pre, attr) {
            var name = attr.name;
            var value = attr.value;
            // has multi attibutes
            // make it array of attribute
            if (value.match(/ /) && attr.name != "elementName") {
              value = value.split(' ');
            }
            switch (value) {
              case "null":
                value = null;
                break;
              case "true":
                value == 'true' ? value = true : value = false;
                break;
              case "false":
                value == 'false' ? value = false : value = true;
                break;
              case "":
                value = value;
                // console.log(value);
                break;
              default:
                isNaN(value) == true ? value = value : value = Number(value);
            }
            // if attr already exists
            // merge it
            if (pre[name]) {
              if (Array.isArray(pre[name])) {
                // already array, push to last
                pre[name].push(value);
              } else {
                // single value, make it array
                pre[name] = [pre[name], value];
              }
            } else {
              // not exist, put it
              pre[name] = value;
            }

            return pre;
          }, {});


          Object.keys(node.props).map(function (key) {
            var result = object = {};
            var value = node.props[key];
            if (key.indexOf('_') !== -1) {
              var arr = key.split('_');
              for (var i = 0; i < arr.length - 1; i++) {
                object = object[arr[i]] = {};
              }
              object[arr[arr.length - 1]] = value;
              MergeRecursive(node.props, result);
              delete node.props[key];
            }

          });

          function MergeRecursive(obj1, obj2) {
            for (var p in obj2) {
              try {
                // Property in destination object set; update its value.
                if (obj2[p].constructor == Object) {
                  obj1[p] = MergeRecursive(obj1[p], obj2[p]);

                } else {
                  obj1[p] = obj2[p];

                }
              } catch (e) {
                // Property in destination object not set; create it and set its value.
                obj1[p] = obj2[p];

              }
            }
            return obj1;
          }
          if (node.props.id) {
            node.id = node.props.id;
          }
        }
        if (unary) {
          // if this tag dosen't have end tag
          // like <img src="hoge.png"/>
          // add to parents
          var parent = bufArray[0] || results;
          if (parent.nodes === undefined) {
            parent.nodes = [];
          }
          parent.nodes.push(node);
        } else {
          bufArray.unshift(node);
        }
      },
      end: function (tag) {
        debug(tag);
        // merge into parent tag
        var node = bufArray.shift();
        if (node.tag !== tag) console.error('invalid state: mismatch end tag');

        if (bufArray.length === 0) {
          results.nodes.push(node);
        } else {
          var parent = bufArray[0];
          if (parent.nodes === undefined) {
            parent.nodes = [];
          }
          parent.nodes.push(node);
        }
      },
      chars: function (text) {
        debug(text);
        // var node = {
        //   node: 'text',
        //   text: text,
        //   element: 'text'
        // };
        if (bufArray.length === 0) {
          var node = {
            node: 'element',
            tag: 'kp_generic',
            element: 'generic',
            id: generateUID(),
            props: {
              content: text
            }
          };

          results.nodes.push(node);
        } else {
          var parent = bufArray[0];
          if (parent.child === undefined) {
            parent.props = parent.props || {}
            parent.props.content = "";
          }
          text = text.replace(/(&rpl&;(i|u|a|b))/gi, '<$2');
          text = text.replace(/(&rpl&;\/?(i|u|a|b))/gi, '</$2');
          text = text.replace(/((i|u|a|b)\/?&rpr&;)/gi, '$2>');
          text = text.replace(/\[br\]/gi, '<br>');
          //replace for span items
          text = text.replace(/&rpl&;/g, '<');
          text = text.replace(/&rpr&;/g, '>');
          parent.props.content = text;
        }
      },
      comment: function (text) {
        debug(text);
        var node = {
          node: 'comment',
          text: text,
        };
        var parent = bufArray[0];
        if (parent.nodes === undefined) {
          parent.nodes = [];
        }
        parent.nodes.push(node);
      },
    });
    return results;
  };


  global.json2html = function json2html(json, elementOccurences) {
    // Empty Elements - HTML 4.01
    var empty = ['area', 'base', 'basefont', 'br', 'col', 'frame', 'hr', 'img', 'input', 'isindex', 'link', 'meta', 'param', 'embed'];
    elementOccurences = elementOccurences || {}
    elementOccurences[json.element] = elementOccurences[json.element] && (elementOccurences[json.element] + 1) || 1

    var level = elementOccurences[json.element]

    var nodes = '';
    if (json.nodes) {
      nodes = json.nodes.map(function (c) {
        return json2html(c, elementOccurences);
      }).join('');
    }

    var props = '';
    if (json.props) {
      props = Object.keys(json.props).map(function (key) {
        var value = json.props[key];
        if (Array.isArray(value)) value = value.join(' ');
        if (key == "content") return;
        return key + '=' + q(value);
      }).join(' ');
      if (props !== '') props = ' ' + props;
    }

    if (json.element !== 'root') { // avoid root element
      var element = json.element;
      // if (empty.indexOf(tag) > -1) {
      //   // empty element
      //   return '<' + json.tag + attr + '/>';
      // }

      // non empty element
      var open = '[kp_' + element;

      if (level > 1) {
        open += '_' + (level - 1)
      }

      var attr = '';
      if (json.props) {
        attr = Object.keys(json.props).map(function (key) {
          var value = json.props[key];
          if (Array.isArray(value)) value = value.join(' ');
          if (key == "content") return;
          //Remove id in props object added unwantedly after shortcodeto JSON
          if (key == "id") return;
          if ((typeof value === "object") && (value !== null)) {
            return getKeys(value, key);
          }
          return key + '=' + q(value);
        }).join(' ');
        if (attr !== '') attr = ' ' + attr;
      }
      if (json.id) {
        attr += ' id="' + json.id + '"';
        // attr += ' level="' + level + '"'

      }
      if (json.templateId)
        attr += ' templateId="' + json.templateId + '"';


      open += ' ' + attr + ' ]';

      if (json.props && json.props.content != null) {
        nodes = json.props.content;
      }
      // var open = '[kp_' + element + attr + ']';
      var levelSuffix = ''
      if (level > 1) {
        levelSuffix = '_' + (level - 1)
      }
      var close = '[/kp_' + element + levelSuffix + ']';

      elementOccurences[json.element] -= 1 // deduct count when exiting recursion 

      return open + nodes + close;
    }

    if (json.element === 'text') {
      return json.text;
    }

    if (json.element === 'comment') {
      return '<!--' + json.text + '-->';
    }

    if (json.element === 'root') {
      return nodes;
    }

    function getKeys(obj, startString) {
      all = {};
      var attr = "";

      function get(obj, startString) {
        var keys = Object.keys(obj);
        for (var i = 0, l = keys.length; i < l; i++) {
          var key = keys[i];
          all[startString + "_" + key] = true;
          var value = obj[key];
          if (value === false) {
            continue;
          }
          if (value instanceof Object) {
            get(value, startString + "_" + key);
          } else {
            attr = attr + ' ' + startString + "_" + key + '=' + q(value);
          }
        }
      }
      get(obj, startString);
      return attr;
    }
  };
})(this);