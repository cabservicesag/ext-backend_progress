define(['jquery', 'TYPO3/CMS/Core/Ajax/AjaxRequest'], function (jquery, AjaxRequest) {
    var ProgressBarToolbarItem = {
        fetching: false
    };

    ProgressBarToolbarItem.init = function () {
      // Initialize what needs to be
      console.log('Initialize ProgressBarToolbarItem');
    }

    const renderProgress = function (jsonData, renderFunction) {
        if (typeof jsonData === "string") {
            jsonData = JSON.parse(jsonData);
        }

        var $container = jquery('#t3-backend-progress-container');
        if (typeof renderFunction !== "undefined") {
            renderFunction($container, jsonData);
        } else {
            $container.empty();
          // TODO: move this to some js templating engine?
            for (var item in jsonData) {
                if(typeof jsonData[item]['currentStep'] !== "undefined") {
                let singleItem = jsonData[item];
                // Do some rendering
                  $container.append(
                    `<div class = "t3-backend-progress-item">
                        <div class = "t3-backend-progress-bar-container">
                          <div class="callout-warning" style = "border:1px darkgrey; height:20px;">
                            <div class="callout-success" style="text-align:center; position: relative;top:1px; height:18px;width:${(singleItem['currentStep'] / singleItem['steps'] * 100)}%;" class = "t3-backend-progress-bar" > ${singleItem['currentStep']}/${singleItem['steps']}</div>
                          </div>
                          <div class = "t3-backend-progress-bar-label" > ${singleItem['label']} </div>
                        </div>
                      </div>`
                  );
              }
            }
        }
    }

    ProgressBarToolbarItem.checkProgressStatus = function () {
        console.log('Check Progress Status callback')
      if (ProgressBarToolbarItem.fetching) {
        return;
      }
      ProgressBarToolbarItem.fetching = true;
      // Call the backend ajax to initialize the view
      let request = new AjaxRequest(TYPO3.settings.ajaxUrls.backend_progress);

      try {
        let promise = request.post({});

        promise.then(async function (response) {
          // we can just try, if it fails, we will refetch and retry on next run
            var responseText = await response.resolve();
            renderProgress(responseText);
            console.log(responseText);
            ProgressBarToolbarItem.fetching = false;
        });
      } catch(e) {
        ProgressBarToolbarItem.fetching = false;
      }
    }

    //ProgressBarToolbarItem.checkProgressStatus();
    setInterval(ProgressBarToolbarItem.checkProgressStatus, 1000);

    return ProgressBarToolbarItem;
});
