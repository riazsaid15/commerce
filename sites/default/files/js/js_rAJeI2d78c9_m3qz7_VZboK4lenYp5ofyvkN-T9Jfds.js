/**
 * @file
 * Provides an event handler for hidden elements in dropdown menus.
 */

(function ($, Drupal, Bootstrap) {
  'use strict';

  /**
   * The list of supported events to proxy.
   *
   * @type {Array}
   */
  var events = [
    // MouseEvent.
    'click', 'dblclick', 'mousedown', 'mouseenter', 'mouseleave', 'mouseup', 'mouseover', 'mousemove', 'mouseout',

    // KeyboardEvent.
    'keypress', 'keydown', 'keyup'
  ];

  /**
   * Bootstrap dropdown behaviors.
   *
   * Proxy any dropdown element events that should actually be fired on the
   * original target (e.g. button, submits, etc.). This allows any registered
   * event callbacks to be fired as they were intended (despite the fact that
   * the markup has been changed to work with Bootstrap).
   *
   * @see \Drupal\bootstrap\Plugin\Preprocess\BootstrapDropdown::preprocessLinks
   *
   * @type {Drupal~behavior#bootstrapDropdown}
   */
  Drupal.behaviors.bootstrapDropdown = {
    attach: function (context) {
      var elements = context.querySelectorAll('.dropdown [data-dropdown-target]');
      for (var k in elements) {
        if (!elements.hasOwnProperty(k)) {
          continue;
        }
        var element = elements[k];
        for (var i = 0, l = events.length; i < l; i++) {
          var event = events[i];
          element.removeEventListener(event, this.proxyEvent);
          element.addEventListener(event, this.proxyEvent);
        }
      }
    },

    /**
     * Proxy event handler for bootstrap dropdowns.
     *
     * @param {Event} e
     *   The event object.
     */
    proxyEvent: function (e) {
      // Ignore tabbing.
      if (e.type.match(/^key/) && (e.which === 9 || e.keyCode === 9)) {
        return;
      }
      var target = e.currentTarget.dataset && e.currentTarget.dataset.dropdownTarget || e.currentTarget.getAttribute('data-dropdown-target');
      if (target) {
        e.preventDefault();
        e.stopPropagation();
        var element = target && target !== '#' && document.querySelectorAll(target)[0];
        if (element) {
          Bootstrap.simulate(element, e.type, e);
        }
        else if (Bootstrap.settings.dev && window.console && !e.type.match(/^mouse/)) {
          window.console.debug('[Drupal Bootstrap] Could not find a the target:', target);
        }
      }
    }
  }

})(jQuery, Drupal, Drupal.bootstrap);
;
/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/

(function ($, Drupal, displace) {
  function TableHeader(table) {
    var $table = $(table);

    this.$originalTable = $table;

    this.$originalHeader = $table.children('thead');

    this.$originalHeaderCells = this.$originalHeader.find('> tr > th');

    this.displayWeight = null;
    this.$originalTable.addClass('sticky-table');
    this.tableHeight = $table[0].clientHeight;
    this.tableOffset = this.$originalTable.offset();

    this.$originalTable.on('columnschange', { tableHeader: this }, function (e, display) {
      var tableHeader = e.data.tableHeader;
      if (tableHeader.displayWeight === null || tableHeader.displayWeight !== display) {
        tableHeader.recalculateSticky();
      }
      tableHeader.displayWeight = display;
    });

    this.createSticky();
  }

  function forTables(method, arg) {
    var tables = TableHeader.tables;
    var il = tables.length;
    for (var i = 0; i < il; i++) {
      tables[i][method](arg);
    }
  }

  function tableHeaderInitHandler(e) {
    var $tables = $(e.data.context).find('table.sticky-enabled').once('tableheader');
    var il = $tables.length;
    for (var i = 0; i < il; i++) {
      TableHeader.tables.push(new TableHeader($tables[i]));
    }
    forTables('onScroll');
  }

  Drupal.behaviors.tableHeader = {
    attach: function attach(context) {
      $(window).one('scroll.TableHeaderInit', { context: context }, tableHeaderInitHandler);
    }
  };

  function scrollValue(position) {
    return document.documentElement[position] || document.body[position];
  }

  function tableHeaderResizeHandler(e) {
    forTables('recalculateSticky');
  }

  function tableHeaderOnScrollHandler(e) {
    forTables('onScroll');
  }

  function tableHeaderOffsetChangeHandler(e, offsets) {
    forTables('stickyPosition', offsets.top);
  }

  $(window).on({
    'resize.TableHeader': tableHeaderResizeHandler,

    'scroll.TableHeader': tableHeaderOnScrollHandler
  });

  $(document).on({
    'columnschange.TableHeader': tableHeaderResizeHandler,

    'drupalViewportOffsetChange.TableHeader': tableHeaderOffsetChangeHandler
  });

  $.extend(TableHeader, {
    tables: []
  });

  $.extend(TableHeader.prototype, {
    minHeight: 100,

    tableOffset: null,

    tableHeight: null,

    stickyVisible: false,

    createSticky: function createSticky() {
      var $stickyHeader = this.$originalHeader.clone(true);

      this.$stickyTable = $('<table class="sticky-header"/>').css({
        visibility: 'hidden',
        position: 'fixed',
        top: '0px'
      }).append($stickyHeader).insertBefore(this.$originalTable);

      this.$stickyHeaderCells = $stickyHeader.find('> tr > th');

      this.recalculateSticky();
    },
    stickyPosition: function stickyPosition(offsetTop, offsetLeft) {
      var css = {};
      if (typeof offsetTop === 'number') {
        css.top = offsetTop + 'px';
      }
      if (typeof offsetLeft === 'number') {
        css.left = this.tableOffset.left - offsetLeft + 'px';
      }
      return this.$stickyTable.css(css);
    },
    checkStickyVisible: function checkStickyVisible() {
      var scrollTop = scrollValue('scrollTop');
      var tableTop = this.tableOffset.top - displace.offsets.top;
      var tableBottom = tableTop + this.tableHeight;
      var visible = false;

      if (tableTop < scrollTop && scrollTop < tableBottom - this.minHeight) {
        visible = true;
      }

      this.stickyVisible = visible;
      return visible;
    },
    onScroll: function onScroll(e) {
      this.checkStickyVisible();

      this.stickyPosition(null, scrollValue('scrollLeft'));
      this.$stickyTable.css('visibility', this.stickyVisible ? 'visible' : 'hidden');
    },
    recalculateSticky: function recalculateSticky(event) {
      this.tableHeight = this.$originalTable[0].clientHeight;

      displace.offsets.top = displace.calculateOffset('top');
      this.tableOffset = this.$originalTable.offset();
      this.stickyPosition(displace.offsets.top, scrollValue('scrollLeft'));

      var $that = null;
      var $stickyCell = null;
      var display = null;

      var il = this.$originalHeaderCells.length;
      for (var i = 0; i < il; i++) {
        $that = $(this.$originalHeaderCells[i]);
        $stickyCell = this.$stickyHeaderCells.eq($that.index());
        display = $that.css('display');
        if (display !== 'none') {
          $stickyCell.css({ width: $that.css('width'), display: display });
        } else {
          $stickyCell.css('display', 'none');
        }
      }
      this.$stickyTable.css('width', this.$originalTable.outerWidth());
    }
  });

  Drupal.TableHeader = TableHeader;
})(jQuery, Drupal, window.Drupal.displace);;
/**
 * @file
 * Overrides core/misc/vertical-tabs.js.
 */

(function ($, Drupal) {
  "use strict";

  var createSticky = Drupal.TableHeader.prototype.createSticky;
  Drupal.TableHeader.prototype.createSticky = function () {
    createSticky.call(this);
    this.$stickyTable.addClass(this.$originalTable.attr('class')).removeClass('sticky-enabled sticky-table');
  };

})(window.jQuery, window.Drupal);
;
/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/

(function ($, Drupal, window) {
  function TableResponsive(table) {
    this.table = table;
    this.$table = $(table);
    this.showText = Drupal.t('Show all columns');
    this.hideText = Drupal.t('Hide lower priority columns');

    this.$headers = this.$table.find('th');

    this.$link = $('<button type="button" class="link tableresponsive-toggle"></button>').attr('title', Drupal.t('Show table cells that were hidden to make the table fit within a small screen.')).on('click', $.proxy(this, 'eventhandlerToggleColumns'));

    this.$table.before($('<div class="tableresponsive-toggle-columns"></div>').append(this.$link));

    $(window).on('resize.tableresponsive', $.proxy(this, 'eventhandlerEvaluateColumnVisibility')).trigger('resize.tableresponsive');
  }

  Drupal.behaviors.tableResponsive = {
    attach: function attach(context, settings) {
      var $tables = $(context).find('table.responsive-enabled').once('tableresponsive');
      if ($tables.length) {
        var il = $tables.length;
        for (var i = 0; i < il; i++) {
          TableResponsive.tables.push(new TableResponsive($tables[i]));
        }
      }
    }
  };

  $.extend(TableResponsive, {
    tables: []
  });

  $.extend(TableResponsive.prototype, {
    eventhandlerEvaluateColumnVisibility: function eventhandlerEvaluateColumnVisibility(e) {
      var pegged = parseInt(this.$link.data('pegged'), 10);
      var hiddenLength = this.$headers.filter('.priority-medium:hidden, .priority-low:hidden').length;

      if (hiddenLength > 0) {
        this.$link.show().text(this.showText);
      }

      if (!pegged && hiddenLength === 0) {
        this.$link.hide().text(this.hideText);
      }
    },
    eventhandlerToggleColumns: function eventhandlerToggleColumns(e) {
      e.preventDefault();
      var self = this;
      var $hiddenHeaders = this.$headers.filter('.priority-medium:hidden, .priority-low:hidden');
      this.$revealedCells = this.$revealedCells || $();

      if ($hiddenHeaders.length > 0) {
        $hiddenHeaders.each(function (index, element) {
          var $header = $(this);
          var position = $header.prevAll('th').length;
          self.$table.find('tbody tr').each(function () {
            var $cells = $(this).find('td').eq(position);
            $cells.show();

            self.$revealedCells = $().add(self.$revealedCells).add($cells);
          });
          $header.show();

          self.$revealedCells = $().add(self.$revealedCells).add($header);
        });
        this.$link.text(this.hideText).data('pegged', 1);
      } else {
          this.$revealedCells.hide();

          this.$revealedCells.each(function (index, element) {
            var $cell = $(this);
            var properties = $cell.attr('style').split(';');
            var newProps = [];

            var match = /^display\s*:\s*none$/;
            for (var i = 0; i < properties.length; i++) {
              var prop = properties[i];
              prop.trim();

              var isDisplayNone = match.exec(prop);
              if (isDisplayNone) {
                continue;
              }
              newProps.push(prop);
            }

            $cell.attr('style', newProps.join(';'));
          });
          this.$link.text(this.showText).data('pegged', 0);

          $(window).trigger('resize.tableresponsive');
        }
    }
  });

  Drupal.TableResponsive = TableResponsive;
})(jQuery, Drupal, window);;
/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/

(function (Drupal) {
  Drupal.theme.checkbox = function () {
    return "<input type=\"checkbox\" class=\"form-checkbox\"/>";
  };
})(Drupal);;
/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/

(function ($, Drupal) {
  Drupal.behaviors.tableSelect = {
    attach: function attach(context, settings) {
      $(context).find('th.select-all').closest('table').once('table-select').each(Drupal.tableSelect);
    }
  };

  Drupal.tableSelect = function () {
    if ($(this).find('td input[type="checkbox"]').length === 0) {
      return;
    }

    var table = this;
    var checkboxes = void 0;
    var lastChecked = void 0;
    var $table = $(table);
    var strings = {
      selectAll: Drupal.t('Select all rows in this table'),
      selectNone: Drupal.t('Deselect all rows in this table')
    };
    var updateSelectAll = function updateSelectAll(state) {
      $table.prev('table.sticky-header').addBack().find('th.select-all input[type="checkbox"]').each(function () {
        var $checkbox = $(this);
        var stateChanged = $checkbox.prop('checked') !== state;

        $checkbox.attr('title', state ? strings.selectNone : strings.selectAll);

        if (stateChanged) {
          $checkbox.prop('checked', state).trigger('change');
        }
      });
    };

    $table.find('th.select-all').prepend($(Drupal.theme('checkbox')).attr('title', strings.selectAll)).on('click', function (event) {
      if ($(event.target).is('input[type="checkbox"]')) {
        checkboxes.each(function () {
          var $checkbox = $(this);
          var stateChanged = $checkbox.prop('checked') !== event.target.checked;

          if (stateChanged) {
            $checkbox.prop('checked', event.target.checked).trigger('change');
          }

          $checkbox.closest('tr').toggleClass('selected', this.checked);
        });

        updateSelectAll(event.target.checked);
      }
    });

    checkboxes = $table.find('td input[type="checkbox"]:enabled').on('click', function (e) {
      $(this).closest('tr').toggleClass('selected', this.checked);

      if (e.shiftKey && lastChecked && lastChecked !== e.target) {
        Drupal.tableSelectRange($(e.target).closest('tr')[0], $(lastChecked).closest('tr')[0], e.target.checked);
      }

      updateSelectAll(checkboxes.length === checkboxes.filter(':checked').length);

      lastChecked = e.target;
    });

    updateSelectAll(checkboxes.length === checkboxes.filter(':checked').length);
  };

  Drupal.tableSelectRange = function (from, to, state) {
    var mode = from.rowIndex > to.rowIndex ? 'previousSibling' : 'nextSibling';

    for (var i = from[mode]; i; i = i[mode]) {
      var $i = $(i);

      if (i.nodeType !== 1) {
        continue;
      }

      $i.toggleClass('selected', state);
      $i.find('input[type="checkbox"]').prop('checked', state);

      if (to.nodeType) {
        if (i === to) {
          break;
        }
      } else if ($.filter(to, [i]).r.length) {
          break;
        }
    }
  };
})(jQuery, Drupal);;
