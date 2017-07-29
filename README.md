# ScanAppForWeb
This application can scan any document via scanner from web browser. Simply it's a web application twain integration. It provides solutions for
  - Twain Scanner for any ASP.NET application (MVC/Web Form)
  - Web Twain Scanner for PHP Application.
  - Any other web Application
  - DemoProject is full implementation in ASP.NET MVC 5.
# Installation
Process of installation is so straight-forward just have to follow the steps bellow:
  - Download DemoProject Copy SrcFile folder to your project's root (or you can manually install the setup file(Scan_App_SetUp.msi) to your computer. Open the apllication for the first time only from desktop sortcut).
  - Open the html file where you want to do scan.
  - Copy and paste the code bellow
```sh
<button type="button" onclick="scanImage();" class="btn btn-primary btn-lg">Scan</button>
<div id="selectedFiles" class="row" style="padding: 3px"></div>
```
  - Button to initiate scan
  - Then Div that will show all scanned image.
 
```sh
<div class="modal fade scandetail" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div style="max-height:1200px;overflow:scroll;">
                <canvas id="myCanvas"></canvas>
            </div>
        </div>
    </div>
</div>


<div class="modal fade dalert" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                Connection Failed
            </div>
            <div class="modal-body">
                No Scan app application found in your machine please download,install and open first then refresh the browser.
                <a href="~/SrcFile/Scan_App_SetUp.msi" download>Download Files</a>
            </div>
        </div>
    </div>
</div>
```
  - First Modal that will show that larger image of scanned document onclick on a specific document.
  - Second one will pop up autometically when this html page can't connect to our Scan App(Scan_App_SetUp.msi). Thats means you didn't intall it yet. Download and install it from here. 
  - Javascript Files bellow are needed with Jquery and Boothstrap.
 ```sh
        var selDiv = "";
        var storedFiles = [];

        $(document).ready(function () {
            selDiv = $("#selectedFiles");
            $("body").on("click", ".selFile", editFiles);
        });

        var start = function () {
            var i = 0;

            var wsImpl = window.WebSocket || window.MozWebSocket;

            window.ws = new wsImpl('ws://localhost:8181/');
            ws.onmessage = function (e) {
                if (typeof e.data === "string") {
                    //IF Received Data is String
                }
                else if (e.data instanceof ArrayBuffer) {
                   //IF Received Data is ArrayBuffer
                }
                else if (e.data instanceof Blob) {

                    i++;

                    var f = e.data;

                    f.name = "File" + i;

                    storedFiles.push(f);

                    var reader = new FileReader();

                    reader.onload = function (e) {
                        var html = "<div class=\"col-sm-2 text-center\" style=\"border: 1px solid black; margin-left: 2px;\"><img height=\"200px\" width=\"200px\" src=\"" + e.target.result + "\" data-file='" + f.name + "' class='selFile' title='Click to remove'><br/>" + i + "</div>";
                        selDiv.append(html);

                    }
                    reader.readAsDataURL(f);
                }
            };
            ws.onopen = function () {
                //Do whatever u want when connected succesfully
            };
            ws.onclose = function () {
                $('.dalert').modal('show');
            };
        }
        window.onload = start;

        function scanImage() {
            ws.send("1100");
        };

        function editFiles(e) {
            var file = $(this).data("file");
            for (var i = 0; i < storedFiles.length; i++) {
                if (storedFiles[i].name === file) {
                    $('.scandetail').modal('show');
                    var c = document.getElementById("myCanvas");
                    var ctx = c.getContext("2d");
                    var img = new Image();
                    img.src = window.URL.createObjectURL(storedFiles[i]);
                    img.onload = function () {
                        c.width = img.width ;
                        c.height = img.height;
                        ctx.drawImage(img, 0, 0, img.width, img.height);
                    }
                    break;

                }
            }
        };
```
  - Thats It. Done!
  - Click on Scan and it will pop up a window select scanner name at the top left side and click on start scan.

# How It Works 
I have made a desktop apllication(Windows Form) that will work in background. Desktop application will remain connected with browser while it is open. When browser command to scan the desktop apllication scan document and send it to browser.
