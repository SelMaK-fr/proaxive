const canvas = document.querySelector("canvas");
const clearButton = document.querySelector("[data-action=clear]");
const signaturePad = new SignaturePad(canvas);

// Returns signature image as data URL (see https://mdn.io/todataurl for the list of possible parameters)
signaturePad.toDataURL(); // save image as PNG
signaturePad.toDataURL("image/jpeg"); // save image as JPEG
signaturePad.toDataURL("image/jpeg", 0.5); // save image as JPEG with 0.5 image quality
signaturePad.toDataURL("image/svg+xml"); // save image as SVG data url

document.getElementById('save').addEventListener("click", function (event) {
    if (signaturePad.isEmpty()) {
        alert("Please provide signature first.");
    } else {
        let image = signaturePad.toDataURL(); // data:image/png....
        document.getElementById('client_sign').value = image;
        //document.getElementById('results').innerHTML = '<img src="'+image+'"/>';
    }
});

// Clears the canvas
clearButton.addEventListener("click", function (event) {
    signaturePad.clear();
});

// Return svg string without converting to base64
signaturePad.toSVG(); // "<svg...</svg>"
signaturePad.toSVG({includeBackgroundColor: true}); // add background color to svg output

// Draws signature image from data URL (mostly uses https://mdn.io/drawImage under-the-hood)
// NOTE: This method does not populate internal data structure that represents drawn signature. Thus, after using #fromDataURL, #toData won't work properly.
signaturePad.fromDataURL("data:image/png;base64,iVBORw0K...");

// Draws signature image from data URL and alters it with the given options
signaturePad.fromDataURL("data:image/png;base64,iVBORw0K...", { ratio: 1, width: 400, height: 200, xOffset: 100, yOffset: 50 });

// Returns signature image as an array of point groups
const data = signaturePad.toData();

// Draws signature image from an array of point groups
signaturePad.fromData(data);

// Draws signature image from an array of point groups, without clearing your existing image (clear defaults to true if not provided)
signaturePad.fromData(data, { clear: false });

// Unbinds all event handlers
signaturePad.off();

// Rebinds all event handlers
signaturePad.on();