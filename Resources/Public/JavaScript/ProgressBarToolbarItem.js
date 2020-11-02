require(['jquery', 'TYPO3/CMS/Core/Ajax/AjaxRequest'], function (jquery, AjaxRequest) {
    var ProgressBarToolbarItem = {
        fetching: false
    };

    ProgressBarToolbarItem.init = function () {
      // Initialize what needs to be
    }

    ProgressBarToolbarItem.checkProgressStatus = function () {
        if (this.fetching) {
            return;
        }
        this.fetching = true;
      // Call the backend ajax to initialize the view
        var that = this;

        let request = new AjaxRequest(TYPO3.settings.ajaxUrls.backend_progress);

        let promise = request.post();

        promise.then(async function (response) {
            that.fetching = false;
            const responseText = await response.resolve();
            that.renderProgress(responseText);
            console.log(responseText);
        });
    }

    ProgressBarToolbarItem.renderProgress = function (jsonData, renderFunction) {
        if (typeof jsonData === "string") {
            jsonData = JSON.parse(jsonData);
        }

        var $container = jquery('#t3-backend-progress-container');
        if(renderFunction) {
          renderFunction($container, jsonData);
        } else {
          $container.empty();
          // TODO: move this to some js templating engine?
          for (item in jsonData) {
            // Do some rendering
            $container.append(
              `<div class="t3-backend-progress-item">
                <div class="t3-backend-progress-bar-container">
                    <div style="width:${item['currentStep'] / item['steps']}%;" class="t3-backend-progress-bar"></div>
                    <div class="t3-backend-progress-bar-label">${item['label']}</div>
                </div>
              </div>`
            );
          }
        }
    }

    setTimeout(ProgressBarToolbarItem.checkProgressStatus, 100);

    return ProgressBarToolbarItem;
})
