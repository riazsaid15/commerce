/**
 * @file
 * JavaScript functionality for the private message notification block.
 */

/*global jQuery, Drupal, drupalSettings, window*/
/*jslint white:true, this, browser:true*/

Drupal.PrivateMessageNotificationBlock = {};

(function ($, Drupal, drupalSettings, window) {

  "use strict";

  var initialized, notificationWrapper, refreshRate, checkingCount;

  /**
   * Trigger Ajax Commands.
   */
  function triggerCommands(data) {
    var ajaxObject = Drupal.ajax({
      url: "",
      base: false,
      element: false,
      progress: false
    });

    // Trigger any any ajax commands in the response.
    ajaxObject.success(data, "success");
  }

  function updateCount(unreadThreadCount) {
    if (unreadThreadCount) {
      notificationWrapper.addClass("unread-threads");
    }
    else {
      notificationWrapper.removeClass("unread-threads");
    }

    notificationWrapper.find(".private-message-page-link").text(unreadThreadCount);

    // Get the current page title.
    var pageTitle = $("title").text();
    // Check if there are any unread threads.
    if (unreadThreadCount) {
      // Check if the unread thread count is already in the page title.
      if (pageTitle.match(/^\(\d+\)\s/)) {
        // Update the unread thread count in the page title.
        pageTitle = pageTitle.replace(/^\(\d+\)\s/, "(" + unreadThreadCount + ") ");
      }
      else {
        // Add the unread thread count to the URL.
        pageTitle = "(" + unreadThreadCount + ") " + pageTitle;
      }
    }
    // No unread messages.
    else {
      // Check if thread count currently exists in the page title.
      if (pageTitle.match(/^\(\d+\)\s/)) {
        // Remove the unread thread count from the page title.
        pageTitle = pageTitle.replace(/^\(\d+\)\s/, "");
      }
    }

    // Set the updated title.
    $("title").text(pageTitle);
  }

  /**
   * Retrieve the new unread thread count from the server using AJAX.
   */
  function getUnreadThreadCount() {
    if (!checkingCount) {
      checkingCount = true;

      $.ajax({
        url:drupalSettings.privateMessageNotificationBlock.newMessageCountCallback,
        success:function (data) {
          triggerCommands(data);

          checkingCount = false;
          if (refreshRate) {
            window.setTimeout(getUnreadThreadCount, refreshRate);
          }
        }
      });
    }
  }

  Drupal.PrivateMessageNotificationBlock.getUnreadThreadCount = function () {
    getUnreadThreadCount();
  };

  /**
   * Initializes the script.
   */
  function init() {
    if (!initialized) {
      initialized = true;

      notificationWrapper = $(".private-message-notification-wrapper");

      if (drupalSettings.privateMessageNotificationBlock.ajaxRefreshRate) {
        refreshRate = drupalSettings.privateMessageNotificationBlock.ajaxRefreshRate * 1000;
        if (refreshRate) {
          window.setTimeout(getUnreadThreadCount, refreshRate);
        }
      }
    }
  }

  Drupal.behaviors.privateMessageNotificationBlock = {
    attach:function () {

      init();

      Drupal.AjaxCommands.prototype.privateMessageUpdateUnreadThreadCount = function (ajax, response) {
        // Stifles jSlint warning.
        ajax = ajax;

        updateCount(response.unreadThreadCount);
      };
    }
  };
}(jQuery, Drupal, drupalSettings, window));
;
