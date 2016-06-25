/*jslint vars: true, plusplus: true, devel: true, nomen: true, indent: 4, maxerr: 50 */
/*define jQuery */
M.correctwriting_enumeration_editor_form  =  (function ($) {



var self = {
    Y: null,
    answer_number : 0,
    serialyzed_data : "",
    span1 : -1,
    span2 : -1,
    spans : [],
    clickCount : 0,
    pos1 : null,
    wordCount : 0,
    spaceCount : 0,
    previousEnum : -1,
    previous : -1,
    mouse1 : 0,
    mouse2 : 0,
    spansCount : 0,
    colors : ["#000000","#00FF66","#336699","#990000","#CC3366",
                     "#CCFF33","#FF6666","#6666FF","#33FF00","#FF6633"],
    draw : 0,
    drawText : 0,
    arrowPath : './type/correctwriting/pix/arrow1.png',//"http://s8.postimg.org/47fp4s5n5/arrow1.png",
    closePath : './type/correctwriting/pix/close.png',//"http://s10.postimg.org/dja0f0dp1/close.png",
    www_root : null,
    textbutton_widget : null,
    //prevdata : null,
    //data : null,

    /**
     * setups module
     *@param {Object} Y NOT USED! It's needed because moodle passes this object anyway
     * @param {string} _www_root string with www host of moodle
     * (smth like 'http://moodle.site.ru/')
     * @param {string} poasquestion_text_and_button_objname name of qtype_preg_textbutton parent object
     */
    init : function (Y, _www_root) {
        this.www_root = _www_root;
        this.Y = Y;
        this.textbutton_widget = M.poasquestion_text_and_button;
        this.setup_parent_object();
    },

    /**
     * Sets up options of M.poasquestion_text_and_button object
     * This method defines onfirstpresscallback method, that calls on very first
     * press on button, right afted dialog generation
     * oneachpresscallback calls on second and following pressings on button
     */
    setup_parent_object : function () {
        var options = {
            onfirstpresscallback : function () {
                $.ajax({
                    url: self.www_root + '/question/type/correctwriting/enumeditor_form/enumeditor.php',
                    type: "GET",
                    dataType: "text"
                }).done(function (responseText) {
        //            var tmpM = M;
                    $(self.textbutton_widget.dialog).html($.parseHTML(responseText, document, false));
          //          M = $.extend(M, tmpM);
                    var id = parseInt(self.textbutton_widget.current_input[0].getAttribute('id').substring(10),10);
                    self.answer_number = id;
                    self.form.init(self);
                    self.Y.one('input#id_answer').set('value',self.textbutton_widget.data);
                    if(self.Y.one("input[name='enumerations[" + self.answer_number + "]']").get('value') === "") {
                        $.ajax({
                            url: self.www_root + '/question/type/correctwriting/enumeditor_form/ajax_enum_catcher.php',
                            type: "GET",
                            data: { data : self.textbutton_widget.data},
                            dataType: "json",
                            complete : function(jqXHR, textStatus ) {console.log(textStatus);}
                        }).done(function (responseText) {
                            self.data.enumerations.enumerations = responseText;
                            self.helpers.load(self);
                        });
                //        self.ajaxcatcher(self);
                    } else {
                        self.helpers.deserialize(self,self.Y.one("input[name='enumerations[" +
                                                        self.answer_number + "]']").get('value'));
                        self.helpers.load(self);
                    }
                });
            },

            oneachpresscallback : function () {
            },
//
            onclosecallback : function () {
            },

            onopencallback : function () {
            },

            oncancelclicked : function (e) {
                self.form.btn_cancel_clicked(e, self);
            },

            onsaveclicked : function (e) {
                self.form.btn_save_clicked(e, self);
            }
        };
        self.textbutton_widget.setup(options);
    },
//    is_changed : function() {
//        return self.data !== self.prevdata;
//    },
    data: {
        enumerations: {
            enumerations: [],
            count:0,
            add: function(self) {
                var current;
                self.data.enumerations.count++;
                self.data.arrows.arrows[self.data.arrows.arrows.length] = [];
                self.data.closes.closes[self.data.closes.closes.length] = [];
                self.data.enumerations.enumerations[self.data.enumerations.enumerations.length] = [];
                self.data.lines.lines[self.data.lines.lines.length] = [];
                self.clickCount = 0;
                self.data.llines.llines[self.data.llines.llines.length] = [];
                self.previous = -1;
                current = self.form.enumerations.current(self);
                self.data.enumerations.enumerations[current] = [];
                self.data.lines.lines[current] = [];
                if (self.data.enumerations.count > 1) {
                  self.form.enumerations.change(self);
                }
                self.previousEnum = self.form.enumerations.current(self);
                self.Y.one("input#id_remove").set("disabled", false);
            },
            remove: function(self,i) {
                self.data.enumerations.enumerations.splice(i, 1);
                self.data.lines.lines.splice(i, 1);
                self.data.closes.closes.slice(i, 1);
                self.data.arrows.arrows.slice(i, 1);
                self.data.enumerations.count--;
            },
            ranks: function(self) {
                var i,
                    j,
                    k,
                    ranks,
                    bounds,
                    bound,
                    includes;
                ranks = [];
                bounds = [];
                includes = [];
                for (i = 0; i < self.data.enumerations.enumerations.length; i++) {
                    bound = self.data.enumerations.bounds(self,i);
                    bounds[i] = bound;
                    ranks[i] = 0;
                    includes[i] = [];
                }
                for (i = 0; i < ranks.length; i++) {
                    for (j = 0; j < ranks.length; j++) {
                        for (k = 0; k < self.data.enumerations.enumerations[j].length; k++) {
                            if (bounds[i][0] !== -1 &&  i !== j &&
                              self.data.enumerations.enumerations[j][k].length > 0) {
                                if (bounds[i][0] >= self.data.enumerations.enumerations[j][k][0] &&
                                  bounds[i][1] <= self.data.enumerations.enumerations[j][k][1]) {
                                    includes[j][includes[j].length] = i;
                                }
                            }
                        }
                    }
                }
                for (i = 0; i < includes.length; i++) {
                    for (j = 0; j < includes[i].length; j++) {
                        if (includes[includes[i][j]].indexOf(i) !== -1) {
                            includes[i].splice(j,1);
                            j--;
                        }
                    }
                }
                for (i = 0; i < self.data.enumerations.enumerations.length; i++) {
                    ranks[i] = self.data.enumerations.__rank(self,i,includes);
                }
                return ranks;
            },
            __rank: function(self, index, includesarray) {
                var ranks = [],
                    i,
                    max = 0;
                for (i = 0; i < includesarray[index].length; i++) {
                    ranks[ranks.length] = self.data.enumerations.__rank(self,
                      includesarray[index][i],includesarray);
                }
                for (i = 0; i < ranks.length; i++) {
                    max = max > ranks[i]?max:ranks[i];
                }
                max = ranks.length !== 0?max + 1:0;
                return max;
            },
            bounds: function(self, enumeration) {
                var begin,
                    end,
                    i;
                begin = -1;
                end = -1;
                if (self.data.enumerations.enumerations[enumeration].length>0) {
                    begin = 100000000;
                    end = -1;
                    for (i = 0; i < self.data.enumerations.enumerations[enumeration].length; i++) {
                        if(self.data.enumerations.enumerations[enumeration][i].length > 0 &&
                          self.data.enumerations.enumerations[enumeration][i][0] < begin) {
                            begin = self.data.enumerations.enumerations[enumeration][i][0];
                        }
                        if(self.data.enumerations.enumerations[enumeration][i].length > 0 &&
                          self.data.enumerations.enumerations[enumeration][i][1] < begin) {
                            begin = self.data.enumerations.enumerations[enumeration][i][1];
                        }
                        if(self.data.enumerations.enumerations[enumeration][i].length > 0 &&
                          self.data.enumerations.enumerations[enumeration][i][0] > end) {
                            end = self.data.enumerations.enumerations[enumeration][i][0];
                        }
                        if(self.data.enumerations.enumerations[enumeration][i].length > 0 &&
                          self.data.enumerations.enumerations[enumeration][i][1] > end) {
                            end = self.data.enumerations.enumerations[enumeration][i][1];
                        }
                    }
                    if (begin === 100000000 && end === -1) {
                        begin = -1;
                        end = -1;
                    }
                }
                return [begin,end];
            },
            elements: {
                add: function(self) {
                    var number = self.form.enumerations.current(self);
                    self.data.enumerations.enumerations[number]
                    [self.data.enumerations.enumerations[number].length] = [];
                    self.data.lines.lines[number][self.data.lines.lines[number].length] = [];
                    self.data.closes.closes[number][self.data.closes.closes[number].length] = -1;
                    self.data.arrows.arrows[number][self.data.arrows.arrows[number].length] = [];
                },
                remove: function (self, i, j) {
                    self.data.enumerations.enumerations[i].splice(j, 1);
                    self.data.closes.closes[i].splice(j, 1);
                    self.data.arrows.arrows[i].splice(j, 1);
                    self.data.lines.lines[i].splice(j, 1);
                },
                update: function(self, start, finish, count, enumeration, element) {
                    var end,
                        begin,
                        text,
                        i;
                    end = finish;
                    begin = start;
                    if (begin > end) {
                        begin = [end, end = begin][0];
                    }
                    self.data.enumerations.enumerations[enumeration][element] = [begin, end];
                    self.data.lines.lines[enumeration][element] = [];
                    self.data.closes.closes[enumeration][element] = self.data.closes.count - 1;
                    for (i = 0; i < count; i++) {
                        self.data.lines.lines[enumeration][element]
                        [self.data.lines.lines[enumeration][element].length] =
                        self.data.lines.count - 1 - i;
                    }
                    if(enumeration === self.form.enumerations.current(self)) {
                        text = self.Y.one("#elements #element_" + element);
                        text.set("text", "element #" + element + " [" + begin + "," + end + "]");
                    }
                }
            }
        },
        lines: {
          lines: [],
          count: 0
        },
        llines: {
          llines: [],
          count: 0
        },
        arrows: {
          arrows: [],
          count: 0
        },
        closes: {
          closes: [],
          count: 0
        },
    },
    form: {
        init: function(self) {
            self.Y.one("a.ajaxcatcher").on('click', function () {
                this.helpers.ajaxcatcher(this);
            },self);
            self.Y.one("input#id_add").on('click', function () {
                this.form.enumerations.add(this);
            },self);
            self.Y.one('input#id_answer').on('valuechange', function () {
                this.form.enumerations.update(this);
            },self);
            self.Y.one("input#id_remove").on('click', function () {
                this.form.enumerations.remove_question(this);
            },self);
        },
        enumerations: {
            add: function(self) {
                var div = self.Y.Node.create('<div>'),
                    text = self.Y.Node.create('<label>'),
                    foo = self.Y.one('#enums'),
                    element = self.Y.Node.create('<input>'),
                    pos,
                    pos1,
                    count,
                    i;
                text.set("id", "enumeration_" + self.data.enumerations.count)
                    .set("text", "enumeration #" + self.data.enumerations.count);
                element.set("id", "id_radio_" + self.data.enumerations.count)
                        .set("type", "radio")
                        .set("value", String(self.data.enumerations.count))
                        .set('checked', true)
                        .set("name", "enumerations");

                element.on("click", function () {
                    this.form.enumerations.change(this);
                },self);
                
                self.data.enumerations.add(self);
                div.set("id", "id_div_" + (self.data.enumerations.count - 1));
                div.appendChild(element);
                div.appendChild(text);
                foo.appendChild(div);
                pos = document.getElementById("enumeration_" + (self.data.enumerations.count - 1)).getBoundingClientRect();
                pos1 = self.Y.one(".ui-dialog").getDOMNode().getBoundingClientRect();
                pos = [pos.right , pos.top - Math.abs(pos1.top) - 30];
                pos1 = [pos[0], pos[1]];
                pos1[0] += 20;
                foo = self.Y.one("#enums #id_div_" + (self.data.enumerations.count - 1) + " #id_radio_" +
                                  (self.data.enumerations.count - 1));
                foo.set("checked", true);
                count = self.form.lines.lines(self,pos, pos1, self.form.enumerations.current(self),true);
                for (i = 0; i < count; i++) {
                    self.data.llines.llines[self.data.enumerations.count - 1]
                        [self.data.llines.llines[self.data.enumerations.count - 1].length] =
                        self.data.lines.count - 1 - i;
                }
            },
            remove: function(self) {
                var i = 0,
                    j = 0,
                    foo,
                    foo1,
                    foo2,
                    result = false,
                    k,
                    pos1,
                    pos,
                    count;
                for (i = 0; i < self.data.enumerations.count && !result; i++) {
                    foo = self.Y.one("#enums #id_div_" + i + " #id_radio_" + i);
                    if (foo !== null && foo.get('checked')) {
                        foo = self.Y.one("#enums #id_div_" + i);
                        foo.remove();
                        self.form.legend.remove(self,i);
                        self.form.enumerations.removeEnumerationView(self,i);
                        self.form.enumerations.__remove(self,i);
                        for (j = i + 1; j < self.data.enumerations.count; j++) {
                            foo = self.Y.one("#enums #id_div_" + j + " #id_radio_" + j);
                            foo1 = self.Y.one("#enums #id_div_" + j + " #enumeration_" + j);
                            foo2 = self.Y.one("#enums #id_div_" + j);
                            foo.set("id", "id_radio_" + (j - 1));
                            foo2.set("id", "id_div_" + (j - 1));
                            foo1.set("id", "enumeration_" + (j - 1));
                            foo1.set("text", "enumeration #" + (j - 1));
                            pos = document.getElementById('enumeration_' + (j-1)).getBoundingClientRect();
                            pos = [pos.right, pos.top];
                            pos1 = [pos[0], pos[1]];
                            pos1[0] += 30;
                            count = self.drawLines(self, pos, pos1, j - 1);
                            self.form.legend.remove(self,j);
                            self.form.enumerations.removeEnumerationView(self,j);
                            self.data.llines.llines[j - 1] = [];
                            for (k = 0; k < count; k++) {
                                self.data.llines.llines[j - 1][self.data.llines.llines[j - 1].length] =
                                    self.data.llines.count - 1 - k;
                            }
                        }
                        self.data.enumerations.remove(self, i);
                        result = true;
                    }
                }
                self.data.llines.llines.splice(self.data.llines.llines.length - 1, 1);
                self.previousEnum = -1;
                self.form.enumerations.upgrade(self);
                if (self.data.enumerations.enumerations.length === 0) {
                    self.Y.one("input#id_remove").set("disabled", true);
                } else {
                    self.Y.one("#enums #id_div_0 #id_radio_0").simulate('click');
                }
            },
            change: function(self) {
                var foo,
                    foo1,
                    data,
                    g,
                    string;
                if (self.previousEnum !== -1) {
                    for (g = 0; g < self.data.enumerations.enumerations[self.previousEnum].length; g++) {
                        self.Y.one("#elements_radio_" + g).remove();
                        self.Y.one("#element_" + g).remove();
                    }
                }
                self.previousEnum = self.form.enumerations.current(self);
                for (g = 0; g < self.data.enumerations.enumerations[self.previousEnum].length; g++) {
                    foo = self.Y.Node.create("<label>")
                        .set("id","element_" + g)
                        .set("text", "element #" + g);
                    if (self.data.enumerations.enumerations[self.previousEnum][g].length > 0) {
                        string = 'element #' + g;
                        string += String(" [" +
                            self.data.enumerations.enumerations[self.previousEnum][g][0] + "," +
                            self.data.enumerations.enumerations[self.previousEnum][g][1] + "]");
                        foo.set('text', string);
                    }
                    data = self.Y.one("#elements");
                    foo1 = self.Y.Node.create("<input>")
                          .set("type","radio")
                          .set("id","elements_radio_" + g)
                          .set("value","" + g)
                          .set("checked", true)
                          .set("name","elements");
                    data.appendChild(foo1)
                        .appendChild(foo);
                }
                self.clickCount = 0;
                self.previous = -1;
                self.form.enumerations.upgrade(self);
            },
            current: function(self) {
                var current = -1;
                if(self.data.enumerations.count > 0) {
                    current = $('#enums input:checked').attr("id");
                    if (typeof(current) !== "undefined") {
                        current = current.slice(9);
                        current = parseInt(current);
                    } else {
                        current = -1;
                    }
                }
                return current;
            },
            upgrade : function(self) {
                var i,
                j,
                pos1,
                pos2,
                posa1,
                posa2,
                count;
                self.form.legend.update(self);
                for (i = 0; i < self.data.enumerations.enumerations.length; i++) {
                    self.form.enumerations.removeEnumerationView(self,i);
                    for (j = 0; j < self.data.enumerations.enumerations[i].length;j++) {
                        pos1 = self.data.enumerations.enumerations[i][j][0];
                        pos2 = self.data.enumerations.enumerations[i][j][1];
                        posa1 = self.form.position.position(self,pos1);
                        posa2 = self.form.position.position(self,pos2);
                        posa1[0] = posa1[2];
                        posa2[0] = posa2[3];
                        count = self.form.lines.lines(self,posa1, posa2, i);
                        self.form.arrows.add(self,posa1,posa2, i, j);
                        self.form.close.add(self,posa1,posa2, i, j);
                        self.data.enumerations.elements.update(self,pos1,pos2,count, i, j);

                    }
                }
            },
            update : function(self) {
                var foo = self.Y.one("#id_answer"),
                    data = self.Y.one("#words"),
                    words,
                    g,
                    text,
                    length = 80,
                    count,
                    i,
                    max,
                    ranks;
                text = '' + foo.get('value').replace(/ +(?= )/g,'');
                text = text.replace(/^ +/g,'');
                length = $("#work").width()/$('#width').width();
                foo.set('value', text);
                words = foo.get('value').split(" ");
                self.spaceCount = 0;
                self.wordCount = 0;
                data.get('childNodes').remove();
                count = foo.get('value').length / length >> 0;
                if (foo.get('value').length % length !== 0) {
                    count++;
                }
                self.spansCount = count;
                max = 0;
                ranks = self.data.enumerations.ranks(self);
                for(i = 0; i < ranks.length; i++) {
                    max = Math.max(max, ranks[i]);
                }
                max++;
                for (i = 0; i < count; i++) {
                    for (g = 0; g < max; g++) {
                        text = self.Y.Node.create('<br>');
                        data.appendChild(text);
                    }
                    text = self.Y.Node.create('<span>');
                    text.set('id', 'word_' + i);
                    text.set('text', foo.get('value').substring(length * i, (i + 1) * length - 1));
                    text.addClass("show");
                    text.on('mouseup', function (ev,self) {
                        self.mouse2 = self.form.get_mouse_pos(ev,self);
                        self.span2 = this.get('id');
                        self.form.action(self);
                    },text,self);

                    text.on('mousedown', function(ev,self) {
                        self.mouse1 = self.form.get_mouse_pos(ev,self);
                        self.span1 = this.get('id');
                    },text,self);

                    data.appendChild(text).appendChild(' ');
                }
                self.form.enumerations.upgrade(self);
            },
            removeEnumerationView : function(self,enumeration) {
                var i;
                for (i = 0; i < self.data.enumerations.enumerations[enumeration].length; i++) {
                    self.form.enumerations.elements.removeElementView(self,enumeration, i);
                }
            },
            __remove: function(self, enumeration) {
                    var g;
                    self.form.enumerations.removeEnumerationView(self,enumeration);
                    for (g = 0; g < self.data.enumerations.enumerations[enumeration].length; g++) {
                       self.Y.one("#elements #elements_radio_" + g).remove();
                       self.Y.one("#elements #element_" + g).remove();
                    }
                },
            remove_question : function(self) {
                var answer = confirm("Are you really decided to delete enumeration?");
                if (answer) {
                    self.form.enumerations.remove(self);
                }
            },
            elements: {
                add: function(self) {
                    var number = self.form.enumerations.current(self),
                        text = self.Y.Node.create("<label>"),
                        foo = self.Y.one("#elements"),
                        element;
                    number = self.data.enumerations.enumerations[number].length;
                    text.set("id", "element_" + number);
                    text.set("text", "element #" + number);
                    element = self.Y.Node.create("<input>");
                    element.set("type", "radio");
                    element.set("id", "elements_radio_" + number);
                    element.set("text", String(number));
                    element.set('checked', true);
                    element.set('name', "elements");
                    foo.appendChild(element);
                    foo.appendChild(text);
                    self.data.enumerations.elements.add(self);
                },
                removeElementView : function(self,enumeration,element) {
                    var i;
                    for (i = 0; i < self.data.lines.lines[enumeration][element].length; i++) {
                      self.Y.one("#line_" + self.data.lines.lines[enumeration][element][i]).remove();
                    }
                    self.data.lines.lines[enumeration][element] = [];
                    for (i = 0; i < self.data.arrows.arrows[enumeration][element].length; i++) {
                        self.Y.one("#arrows #arrow_" + self.data.arrows.arrows[enumeration][element][i]).remove();
                    }
                    self.data.arrows.arrows[enumeration][element] = [];
                    if (self.data.closes.closes[enumeration][element] != -1) {
                        self.Y.one("#closes #close_" + self.data.closes.closes[enumeration][element]).remove();
                    }
                    self.data.closes.closes[enumeration][element] = -1;
                },
                replace: function(self, id) {
                    var pos,
                        element,
                        enumeration,
                        side,
                        left,
                        right;
                    pos = self.form.enumerations.elements.byArrow(self,id);
                    element = pos[1];
                    enumeration = pos[0];
                    side = pos[2];
                    pos = [];
                    var pos1 = self.Y.one("#poasquestion_textandbutton_dialog").getDOMNode().getBoundingClientRect();
                    pos = id.getDOMNode().getBoundingClientRect();
                    pos = [pos.right, pos.top - Math.abs(pos1.top)];
                    left = self.form.position.word(self, pos,'left',false)[1];
                    right = self.form.position.word(self,pos,'right',false)[1];
                    pos = [self.data.enumerations.enumerations[enumeration][element][0],
                            self.data.enumerations.enumerations[enumeration][element][1]];
                    pos = side === 'begin' ?[left,pos[1]]:[pos[0],right];
                    if (pos[0] !== -1 && pos[1] !== -1) {
                        self.form.enumerations.elements.set(self,pos[0], pos[1], enumeration, element);
                    } else {
                        self.form.arrows.replace(self,enumeration, element);
                    }
                },
                remove: function(self, element) {
                    var f = parseInt(element.get("id").substring(6), 10),
                        i,
                        j = 0,
                        k = 0,
                        foo,
                        foo1,
                        result = false;
                    for (i = 0; i < self.data.closes.closes.length && !result; i++) {
                        for (j = 0; j < self.data.closes.closes[i].length && !result; j++) {
                            if (self.data.closes.closes[i][j] !== null && self.data.closes.closes[i][j] === f) {
                                self.form.enumerations.elements.removeElementView(self,i,j);
                                if (i === self.form.enumerations.current(self)) {
                                    foo = self.Y.one("#elements_radio_" + j);
                                    foo1 = self.Y.one("#element_" + j);
                                    foo.remove();
                                    foo1.remove();
                                    for (k = j + 1; k < self.data.enumerations.enumerations[i].length; k++) {
                                        foo = self.Y.one("#elements_radio_" + k);
                                        foo1 = self.Y.one("#element_" + k);
                                        foo.set("id", "elements_radio_" + (k - 1));
                                        foo1.set("id", "element_" + (k - 1));
                                        foo1.set("text", "element #" + (k - 1));
                                        if (self.data.enumerations.enumerations[i][k].length > 0) {
                                            foo1.set("text", "element #" + (k - 1) + " [" +
                                                self.data.enumerations.enumerations[i][k][0] +
                                                "," + self.data.enumerations.enumerations[i][k][1] + "]");
                                        }
                                    }
                                    if (self.data.enumerations.enumerations[i].length > 1) {
                                        self.Y.one("#elements_radio_0").set("checked", true);
                                    }
                                }
                                self.data.enumerations.elements.remove(self,i,j);
                                self.form.enumerations.upgrade(self);
                                result = true;
                            }
                        }
                    }
                },
                current: function(self) {
                    var current = -1;
                    if ( self.form.enumerations.current(self) != -1 ) {
                        current = $("#elements input[type=radio]:checked").get("id");
                        current = current.slice(16);
                        current = parseInt(current);
                    }
                    return current;
                },
                check: function(self, begin, end, enumeration, element) {
                    var i,
                        j,
                        k,
                        g,
                        bound,
                        bounds = [],
                        include,
                        index,
                        someelements,
                        oneelement;
                    if ( enumeration != -1 && element != -1) {
                        for (i = 0; i < self.data.enumerations.enumerations.length; i++) {
                            bound = self.data.enumerations.bounds(self, i);
                            bounds[i] = bound;
                        }
                        for (i = 0; i < bounds.length; i++) {
                            if ( bounds[i].length > 0 && bounds[i][0] !== -1 && i !== enumeration &&
                                (
                                    (begin < bounds[i][0] && end < bounds[i][1] && end >= bounds[i][0]) ||
                                    (begin > bounds[i][0] && end > bounds[i][1] && begin <= bounds[i][1])
                                )
                            ) {
                                return false;
                            }
                        }
                        for (i = 0; i < self.data.enumerations.enumerations.length; i++) {
                            include = 0;
                            index = -1;
                            for (j = 0; j < self.data.enumerations.enumerations[i].length; j++) {
                                if (begin <= self.data.enumerations.enumerations[i][j][0]
                                        && end >= self.data.enumerations.enumerations[i][j][1]
                                        && !(j === element && i === enumeration)) {
                                    include++;
                                    index = j;
                                }
                            }
                            someelements = include > 1 && (begin <= bounds[i][0] && end < bounds[i][1] ||
                                begin > bounds[i][0] && end >= bounds[i][1]);
                            oneelement = include === 1 && (begin <= bounds[i][0] && end < bounds[i][1] ||
                                begin > bounds[i][0] && end >= bounds[i][1]) && 
                                (begin <= self.data.enumerations.enumerations[i][index][0] &&
                                end > self.data.enumerations.enumerations[i][index][1] || 
                                begin < self.data.enumerations.enumerations[i][index][0] && 
                                end >= self.data.enumerations.enumerations[i][index][1]);
                            if ( someelements || oneelement) {
                                return false;
                            }
                        }
                        var newelement = self.data.enumerations.enumerations[enumeration].length === element;
                        var clone;
                        if (!newelement) {
                            clone = self.data.enumerations.enumerations[enumeration][element].slice(0);
                        }
                        self.data.enumerations.enumerations[enumeration][element] = [begin, end];
                        for (i = 0; i < self.data.enumerations.enumerations.length; i++) {
                            for (j = 0; j < self.data.enumerations.enumerations[i].length; j++) {
                                if (self.data.enumerations.enumerations[i][j].length > 0
                                        && self.data.enumerations.enumerations[i][j][0] !== -1) {
                                    for (k = 0; k < self.data.enumerations.enumerations.length; k++) {
                                        include = 0;
                                        index = -1;
                                        for (g = 0; g < self.data.enumerations.enumerations[k].length; g++) {
                                            if (self.data.enumerations.enumerations[k][g].length > 0
                                                    && self.data.enumerations.enumerations[k][g][0] != -1
                                                    && self.data.enumerations.enumerations[i][j][0] 
                                                        <= self.data.enumerations.enumerations[k][g][0]
                                                    && self.data.enumerations.enumerations[i][j][1] 
                                                        >= self.data.enumerations.enumerations[k][g][1]
                                                    && !(j === g && i === k)) {
                                                include++;
                                                index = g;
                                            }
                                        }
                                        someelements = include > 1 && include !== self.data.enumerations.enumerations[k].length;
                                        oneelement = include === 1 && 
                                            (self.data.enumerations.enumerations[i][j][0] <= bounds[k][0] &&
                                            self.data.enumerations.enumerations[i][j][1] < bounds[k][1] ||
                                            self.data.enumerations.enumerations[i][j][0] > bounds[k][0] &&
                                            self.data.enumerations.enumerations[i][j][1] >= bounds[k][1]) &&
                                            (self.data.enumerations.enumerations[i][j][0]
                                                <= self.data.enumerations.enumerations[k][index][0] &&
                                            self.data.enumerations.enumerations[i][j]
                                                > self.data.enumerations.enumerations[k][index][1] ||
                                            self.data.enumerations.enumerations[i][j][0]
                                                < self.data.enumerations.enumerations[k][index][0] &&
                                            self.data.enumerations.enumerations[i][j][1]
                                                >= self.data.enumerations.enumerations[k][index][1]);
                                        if ( someelements || oneelement) {
                                            if (newelement) {
                                                self.data.enumerations.enumerations[enumeration].splice(element,1);
                                            } else {
                                                self.data.enumerations.enumerations[enumeration][element] = clone;
                                            }
                                            return false;
                                        }
                                    }
                                }
                            }
                        }
                        if (newelement) {
                            self.data.enumerations.enumerations[enumeration].splice(element,1);
                        } else {
                            self.data.enumerations.enumerations[enumeration][element] = clone;
                        }
                        for (i = 0; i < self.data.enumerations.enumerations.length; i++) {
                            for (j = 0; j < self.data.enumerations.enumerations[i].length; j++) {
                                if ( self.data.enumerations.enumerations[i][j].length > 0 && !(j == element && i == enumeration) &&
                                (
                                    (begin >= self.data.enumerations.enumerations[i][j][0]
                                     && end <= self.data.enumerations.enumerations[i][j][1] && i == enumeration) ||
                                    (begin <= self.data.enumerations.enumerations[i][j][0]
                                     && end >= self.data.enumerations.enumerations[i][j][1] && i == enumeration) ||
                                    (begin < self.data.enumerations.enumerations[i][j][0]
                                     && end < self.data.enumerations.enumerations[i][j][1]
                                     && end >= self.data.enumerations.enumerations[i][j][0]) ||
                                    (begin > self.data.enumerations.enumerations[i][j][0] 
                                     && end > self.data.enumerations.enumerations[i][j][1] 
                                     && begin <= self.data.enumerations.enumerations[i][j][1]))) {
                                    return false;
                            }
                          }
                        }
                    }
                    return true;
                },
                set: function(self,position1,position2,enumeration, element) {
                    var pos1,
                        pos2,
                        posa1,
                        posa2,
                        count;
                    pos1 = position1;
                    pos2 = position2;
                    if (typeof(pos1) === 'undefined' || typeof(pos2) === 'undefined' ) {
                        if (self.previous != -1 && pos1 != -1) {
                            pos2 = pos1;
                            pos1 = self.previous;
                            self.previous = -1;
                        } else if (typeof(pos1) !== 'undefined') {
                            self.previous = pos1;
                        }
                    }

                    if (typeof(pos1) !== 'undefined' && typeof(pos2) !== 'undefined' ) {
                        if(self.data.enumerations.count === 0) {
                            enumeration = 0;
                            self.form.enumerations.add(self);
                        }
                        if (pos1 > pos2) {
                            pos1 = [pos2,pos2 = pos1][0];
                        }
                        if (self.form.enumerations.elements.check(self,pos1,pos2,enumeration,element)) {
                            if (element === self.data.enumerations.enumerations[enumeration].length) {
                                self.form.enumerations.elements.add(self);
                            }
                            posa1 = self.form.position.position(self,pos1);
                            posa2 = self.form.position.position(self,pos2);
                            posa1[0] = posa1[2];
                            posa2[0] = posa2[3];
                            self.form.enumerations.elements.removeElementView(self,enumeration, element);
                            count = self.form.lines.lines(self,posa1, posa2, enumeration);
                            self.form.arrows.add(self,posa1,posa2,enumeration,element);
                            self.form.close.add(self,posa1,posa2,enumeration,element);
                            self.data.enumerations.elements.update(self,pos1,pos2,count,enumeration,element);
                            self.form.enumerations.update(self);
                        } else {
                            self.form.crossing(self);
                            if (element !== self.data.enumerations.enumerations[enumeration].length) {
                                self.form.arrows.replace(self,enumeration,element);
                            }
                        }
                        self.previous = -1;
                    }
                    self.form.enumerations.upgrade(self);
                },
                byArrow: function(self,id) {
                    id = id.get('id');
                    id = parseInt(id.substring(6),10);
                    for (var i = 0; i <self.data.enumerations.count; i++) {
                        for (var j = 0; j < self.data.arrows.arrows[i].length; j++) {
                            for (var k = 0; k < self.data.arrows.arrows[i][j].length; k++) {
                                if (self.data.arrows.arrows[i][j][k] == id) {
                                    var side = k === 0? 'begin': 'end';
                                    return [i, j, side];
                                }
                            }
                        }
                    }
                    return [-1, -1, 'none'];
                }
            }
        },
        lines: {
            add: function(self, ax, ay, number, isLegend) {
                var lines = self.Y.one("#lines"),
                    line = self.Y.Node.create("<div>"),
                    multiplier = self.data.enumerations.ranks(self)[number];
                multiplier = isLegend === true? 0: self.data.enumerations.ranks(self)[number];
                line.set("id", 'line_' + self.data.lines.count)
                    .setStyle("background-color", self.colors[number])
                    .setStyle("top", String((ay  - 15 * multiplier) + "px"))
                    .setStyle("left", String(ax + "px"))
                    .addClass("vline");
                lines.appendChild(line);
                self.data.lines.count++;
            },
            add_horisontal: function(self, ax, ay, length, number, isLegend) {
                var lines = self.Y.one("#lines"),
                    line = self.Y.Node.create("<div>"),
                    multiplier = self.data.enumerations.ranks(self)[number];
                multiplier = isLegend === true? 0: self.data.enumerations.ranks(self)[number];
                line.set("id", 'line_' + self.data.lines.count)
                    .setStyle("background-color", self.colors[number])
                    .setStyle("top", String((ay - 15 * multiplier) + "px"))
                    .setStyle("left", String(ax + "px"))
                    .setStyle("width", String(length + "px"))
                    .addClass("gline");
                lines.appendChild(line);
                 self.data.lines.count++;
            },
            add_dashed: function(self, ax, ay, length, number, isLegend) {
                var i;
                for (i = 0; i < 10; i++) {
                    self.form.lines.add_horisontal(self,ax + i * length / 10, ay, length / 21, number, isLegend);
                }
                self.form.lines.add_horisontal(self,ax + 41 * length / 42, ay, length / 42, number, isLegend);
                return 11;
            },
            lines: function(self,pos1, pos2, number, isLegend) {
                var count = 3,
                    num = number,
                    dashed = false,
                    max;
                if (number > 9) {
                    num -= 10;
                    dashed = true;
                }
                self.form.lines.add(self, pos1[0], pos1[1], num, isLegend);
                self.form.lines.add(self, pos2[0], pos2[1], num, isLegend);

                if (pos1[1] === pos2[1]) {
                    if (!dashed) {
                        self.form.lines.add_horisontal(self, pos1[0], pos1[1], Math.abs(pos2[0] - pos1[0]),
                              num, isLegend);
                    } else {
                        count += self.form.lines.add_dashed(self,pos1[0], pos1[1],
                                                Math.abs(pos2[0] - pos1[0]), num, isLegend);
                    }
                } else {
                    max = Math.max(document.documentElement.clientWidth,
                                       document.documentElement.scrollWidth);
                    if (!dashed) {
                        self.form.lines.add_horisontal(self,pos1[0], pos1[1], max -
                                  pos1[0], num, isLegend);
                        self.form.lines.add_horisontal(self, 0, pos2[1], pos2[0], num, isLegend);
                        count++;
                    } else {
                        count += self.form.lines.add_dashed(self, pos1[0], pos1[1],
                                 max - pos1[0], num, isLegend);
                        count += self.form.lines.add_dashed(self, 0, pos2[1], pos2[0], num, isLegend);
                    }
                }
                return count;
            },
            line: 0
        },
        arrows: {
            add: function(self, pos, pos1, enumeration, element) {
                var div = self.Y.Node.create("<div>"),
                    img = self.Y.Node.create("<img>"),
                    div2 = self.Y.Node.create("<div>"),
                    multiplier = self.data.enumerations.ranks(self)[enumeration];
                div.set("id", "arrow_" + self.data.arrows.count);
                div.setStyle("position", "absolute");
                div.setStyle("top", pos[1] - 3 - 15 * multiplier);
                div.setStyle("left", pos[0] - 7);
                div.addClass("img");
                img.set("src", self.arrowPath);
                div.appendChild(img);
                self.Y.one("#arrows").appendChild(div);
                var dd = new self.Y.DD.Drag({
                    node: '#' + div.get('id')
                });
                dd.on("drag:end", function () {
                    new self.form.enumerations.elements.replace(self,div);
                });
                self.data.arrows.arrows[enumeration][element] = [];
                self.data.arrows.arrows[enumeration][element]
                    [self.data.arrows.arrows[enumeration][element].length] = self.data.arrows.count;
                self.data.arrows.count++;

                div2 = self.Y.Node.create("<div>");
                div2.set("id", "arrow_" + self.data.arrows.count);
                div2.setStyle("position","absolute");
                div2.setStyle("top", pos1[1] - 3 - 15 * multiplier);
                div2.setStyle("left", pos1[0] - 7);
                div2.addClass("img");
                img = self.Y.Node.create("<img>");
                img.set("src", self.arrowPath);
                div2.appendChild(img);
                self.Y.one("#arrows").appendChild(div2);
                dd = new self.Y.DD.Drag({
                    node: '#' + div2.get('id')
                });
                dd.on("drag:end", function () {
                    new self.form.enumerations.elements.replace(self,div2);
                });
                self.data.arrows.arrows[enumeration][element]
                    [self.data.arrows.arrows[enumeration][element].length] = self.data.arrows.count;
                self.data.arrows.count++;
            },
            replace: function(self,enumeration, element) {
                var arrows,
                    words,
                    position1,
                    position2,
                    multiplier = self.data.enumerations.ranks(self)[enumeration];

                arrows = self.data.arrows.arrows[enumeration][element];
                words = self.data.enumerations.enumerations[enumeration][element];
                position1 = self.form.position.position(self, words[0]);
                position2 = self.form.position.position(self, words[1]);
                self.Y.one("#arrow_" + arrows[0]).setStyle("position","absolute")
                      .setStyle("top", position1[1] - 3 - 15 * multiplier)
                      .setStyle("left", position1[2] - 7);

                self.Y.one("#arrow_" + arrows[1]).setStyle("position","absolute")
                      .setStyle("top", position2[1] - 3 - 15 * multiplier)
                      .setStyle("left", position2[3] - 7);
            }
        },
        close: {
            add: function(self, pos, pos1, enumeration, element) {
                var div = self.Y.Node.create("<div>"),
                    img = self.Y.Node.create("<img>"),
                    multiplier = self.data.enumerations.ranks(self)[enumeration];
                div.set("id", "close_" + self.data.closes.count);
                div.setStyle("position", "absolute");
                div.setStyle("top", pos[1] - 13 - 15 * multiplier);
                div.setStyle("left", Math.abs(pos1[0] - pos[0]) / 2 + pos[0] - 8);

                img.set("src", self.closePath);
                div.on('click', function (ev,params) {
                    var self = params[0];
                    new self.form.enumerations.elements.remove(self, params[1]);
                },self.Y,[self,div]);
                self.Y.one("#closes").appendChild(div);
                self.Y.one("#work #closes #close_" + self.data.closes.count).appendChild(img);
                self.data.closes.closes[enumeration][element] = self.data.closes.count;
                self.data.closes.count++;
            }
        },
        legend: {
            remove: function(self,number) {
                var t;
                for (t = 0; t < self.data.llines.llines[number].length; t++) {
                    self.Y.one("#lines #line_" + self.data.llines.llines[number][t]).remove();
                }
            },
            update: function(self) {
                var pos,
                    i,
                    j;
                for (i = 0; i < self.data.enumerations.enumerations.length; i++) {
                    pos = $("#enumeration_" + i).position();
                    for (j = 0; j < self.data.llines.llines[i].length; j++) {
                        self.Y.one("#line_" + self.data.llines.llines[i][j]).setStyle("top",pos.top);
                    }
                }
            }
        },
        position: {
            position: function(self, number) {
                var result = [-1,-1],
                    count = 0,
                    i,
                    text,
                    startx,
                    x,
                    y,
                    j,
                    rect,
                    width,
                    begin,
                    end;
                for (i = 0; i < self.spansCount; i++) {
                    if (( count + self.form.words_count_span(self, i)) >= number && number >= count) {
                        text = self.Y.one("#work #words #word_" + i).get('text').split(" ");
                        rect = $("#word_" + i ).position();
                        //document.getElementById("word_" + i).getBoundingClientRect();
                        //var pos1 = self.Y.one("#poasquestion_textandbutton_dialog").getDOMNode().getBoundingClientRect();
                        startx = rect.left;
                        x = startx;

                        y = rect.top - 10;
                        width = self.Y.one("#work #width").get('offsetWidth');
                        for (j = 0; j < number - count; j++) {
                            x += width*(text[j].length + 1);
                        }
                        begin = x;
                        end = x + width*(text[number - count].length);
                        x += width*(text[number - count].length/2);
                        result = [x,y,begin,end];
                    }
                    count += self.form.words_count_span(self, i);
                }
                return result;
            },
            word: function(self, position,direction,use_space) {
                var result = [-1,-1],
                    count = 0,
                    find = false,
                    i,
                    j,
                    rect,
                    text,
                    startx,
                    x,
                    y,
                    width,
                    start,
                    end;
                for (i = 0; i < self.spansCount && !find; i++) {
                    count += self.form.words_count_span(self, i);
                    rect = $("#word_" + i).offset();
                    //var pos = self.Y.one("#poasquestion_textandbutton_dialog").getDOMNode().getBoundingClientRect();
                    position = position === 0? [rect.left + 1, rect.top]:position;
                    if (rect.top + $("#width").height() >= position[1]) {
                        count -= self.form.words_count_span(self, i);
                        text = self.Y.one("#work #words #word_" + i).get('text').split(" ");
                        startx = rect.left;
                        x = startx;
                        y = 0;
                        // TODO: добавить учет нескольких строк
                        width = self.Y.one("#work #width").get('offsetWidth');
                        for(j = 0; j < text.length && !find; j++) {
                            start = j === 0 ? 0:x;
                            end =  x + width*(text[j].length);
                            start -= (j>0 && direction == 'left') ?  width*2:0;
                            end += (j<(text.length - 1)&&direction == 'right') ?  3*width:0;
                            if (direction && start < position[0] && position[0] < end && !find) {
                                result = j;
                                find = true;
                                if ((!use_space && direction == "right") || (use_space && direction == "left")) {
                                    find = false;
                                //    result += direction == 'left' ? -1 : 1;
                                }
                                result = [text[result],(result+count)];
                            } else if(!direction && x <= position[0] && position[0] <= (x + width*text[j].length)) {
                                count += j;
                                result = [text[j],count];
                            }
                            x += width*(text[j].length+ 1);
                        }
                    }
                }
                return result;
            }
        },
        get_selection_text: function() {
            var text = "";
            if (window.getSelection) {
                text = window.getSelection().toString();
            } else if (document.selection && document.selection.type != "Control") {
                text = document.selection.createRange().text;
            }
            return text;
        },
        action: function(self) {
            var pos1,
                pos2,
                selection,
                x,
                rect,
                enumeration = -1,
                element = -1;
            selection = self.form.get_selection_text();
            if (selection !== "") {
                pos1 = self.mouse1;
                pos2 = self.mouse2;
                x = self.Y.one("#work #words #word_0").getX();
                rect = document.getElementById("word_" + 0).getBoundingClientRect();
                if ((self.span1 == self.span2 && pos1[0] > pos2[0]) || (self.span2 != self.span1 && pos1[1] > pos2[1])) {
                    pos1 = [pos2,pos2 = pos1][0];
                }
                pos1 = self.form.position.word(self,pos1,'left', selection.charAt(0) ===' ')[1];
                pos2 = self.form.position.word(self,pos2,'right', selection.charAt(selection.length - 1)===' ')[1];
                if (pos1 > pos2) {
                    pos1 = [pos2,pos2 = pos1][0];
                }
                window.getSelection().removeAllRanges();
                enumeration = self.data.enumerations.count === 0 ? 0: self.form.enumerations.current(self);
                element = typeof(self.data.enumerations.enumerations[enumeration]) !== 'undefined' 
                    ? self.data.enumerations.enumerations[enumeration].length : 0;
            } else {
                pos1 = self.form.position.word(self,self.mouse2)[1];
            }
            if (enumeration === -1 || element === -1) {
                enumeration = self.form.enumerations.current(self);
                element = typeof(self.data.enumerations.enumerations[enumeration]) !== 'undefined'
                    ? self.data.enumerations.enumerations[enumeration].length : 0;
            }
            self.form.enumerations.elements.set(self,pos1,pos2,enumeration,element);
        },
        crossing: function() {
            var dialog = new self.Y.Panel({
                width      : 400,
                zIndex     : 9999,
                bodyContent: '<img src="/theme/image.php/clean/core/1410350174/t/block"/>Error!Wrong enumerations order.',
                centered : true,
                visible : false,
                modal : true,
                render : true});
                dialog.onCancel = function (e) {
                        e.preventDefault();
                        this.hide();
                        this.callback = false;
                };
                dialog.show();
        },
        words_count_span: function(self, number) {
            var span = self.Y.one('#work #words #word_' + number).get('text'),
                span1 = null,
                count;
            if (number + 1 !== self.spansCount) {
                span1 = self.Y.one('#work #words #word_' + (number+1)).get('text');
            }
            count = span.split(' ').length;
            if (span1 !== null && span[span.length-1] !== " " && span1[0] !== " ") {
                count--;
            }
            return count;

        },
        btn_cancel_clicked : function (e) {
            e.preventDefault();
            self.textbutton_widget.dialog.dialog("close");
            $('#id_test_regex').html('');
        },
        btn_save_clicked : function (e, self) {
            e.preventDefault();
            self.helpers.serialize(self);
            self.Y.one("input[name='enumerations[" + self.answer_number + "]']").set('value',self.serialyzed_data);
            self.textbutton_widget.data = self.Y.one('input#id_answer').get('value');
            self.textbutton_widget.close_and_set_new_data(self.textbutton_widget.data);
        },
        get_mouse_pos: function(e) {
            var position = [e.pageX, e.pageY];
            return position;
        },
        get_offset_rect: function(self, elem) {
            var box = elem.getBoundingClientRect(),
                body = document.body,
                docElem = document.documentElement,
                scrollTop = window.pageYOffset || docElem.scrollTop || body.scrollTop,
                scrollLeft = window.pageXOffset || docElem.scrollLeft || body.scrollLeft,
                clientTop = docElem.clientTop || body.clientTop || 0,
                clientLeft = docElem.clientLeft || body.clientLeft || 0,
                top  = box.top +  scrollTop - clientTop,
                left = box.left + scrollLeft - clientLeft;
            return { top: Math.round(top), left: Math.round(left) };
        }
    },
    helpers: {
        load: function(self) {
            var enums = self.data.enumerations.enumerations;
            self.data.enumerations.enumerations = [];
            for(var i = 0; i < enums.length; i++) {
                self.form.enumerations.add(self);
                for(var j = 0; j < enums[i].length; j++) {
                    self.form.enumerations.elements.add(self);
                    self.form.enumerations.elements.set(self, enums[i][j][0],enums[i][j][1],i,j);
                }
            }
            var str = $("input#id_answer").val();
            $("input#id_answer").val("");
            $("input#id_answer").val(str);
            //self.form.enumerations.update(self);
        },
        serialize : function(self) {
            var string = "",
                i,
                j;
            for (i = 0; i < self.data.enumerations.enumerations.length; i++) {
                string += ";[";
                for (j = 0; j < self.data.enumerations.enumerations[i].length; j++) {
                    string += "" + self.data.enumerations.enumerations[i][j][0];
                    string += "-";
                    string += "" + self.data.enumerations.enumerations[i][j][1];
                    string += ",";
                }
                string = string.substring(0, string.length - 1);
                string += "]";
            }
            string = string.substring(1);
            self.serialyzed_data = string;
        },
        deserialize : function(self, string) {
            var result = [],
                i,
                j;
            string = string.split(";");
            for (i = 0; i < string.length; i++) {
                result[result.length] = [];
                string[i] = string[i].replace("[", "");
                string[i] = string[i].replace("]", "");
                var elements = string[i].split(",");
                for (j = 0; j < elements.length; j++) {
                    var position = elements[j].split("-");
                    if(position.length == 2 ) {
                        result[result.length - 1][result[result.length - 1].length] =
                                [parseInt(position[0]), parseInt(position[1])];
                    }
                }
            }
            self.data.enumerations.enumerations = result;
        },
        ajaxcatcher : function(self) {
            for(var i = 0; i < self.data.enumerations.enumerations.length; ) {
                self.form.enumerations.remove(self);
            }
            $.ajax({
                url: self.www_root + '/question/type/correctwriting/enumeditor_form/ajax_enum_catcher.php',
                type: "GET",
                data: { data : self.Y.one('input#id_answer').get('value')},
                dataType: "json",
                complete : function(jqXHR, textStatus ) {console.log(textStatus);}
            }).done(function (responseText) {
                self.data.enumerations.enumerations = responseText;
                self.helpers.load(self);
            });
        }
    }

};
return self;

})(jQuery);
