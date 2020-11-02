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
            for (item in jsonData) {
              if(typeof item['currentStep'] !== "undefined") {

              // Do some rendering
                $container.append(
                  `<div class = "t3-backend-progress-item">
                      <div class = "t3-backend-progress-bar-container">
                        <div style = "width:${jsonData[item]['currentStep'] / jsonData[item]['steps']}%;" class = "t3-backend-progress-bar" > </div>
                        <div class = "t3-backend-progress-bar-label" > ${item['label']} </div>
                      </div>
                    </div> `
                );
              } else {
                $container.append(
                  '<div class="t3-backend-progress-item">' +
                    jsonData[item] +
                  '</div>'
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

      let promise = request.post({});

      promise.then(async function (response) {
        // we can just try, if it fails, we will refetch and retry on next run
        try {
          var responseText = await response.resolve();
          renderProgress(responseText);
        } catch(e) {}

        ProgressBarToolbarItem.fetching = false;
        console.log(responseText);
      });
    }

    setInterval(ProgressBarToolbarItem.checkProgressStatus, 1000);

    return ProgressBarToolbarItem;
});
