import './bootstrap';
import feather from 'feather-icons';
import QRCode from 'qrcode';
import { Html5Qrcode } from "html5-qrcode";

window.feather = feather;
window.QRCode = QRCode;
window.Html5Qrcode = Html5Qrcode;

let qrScanner = null;

// Initialize Feather icons when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
    feather.replace();
    initQRCodeGenerators();
});

// Also initialize on page load as backup
window.addEventListener('load', function () {
    feather.replace();
});

// QR Code generation helper
function initQRCodeGenerators() {
    const containers = document.querySelectorAll('[data-qr-content]');
    containers.forEach(container => {
        const content = container.dataset.qrContent;
        const width = parseInt(container.dataset.qrWidth) || 128;

        QRCode.toCanvas(container, content, {
            width: width,
            margin: 2, // Increased margin for better scan-ability
            color: {
                dark: '#0f172a',
                light: '#ffffff'
            }
        }, function (error) {
            if (error) console.error('QR Code error:', error);
        });
    });
}

window.generateQRCode = function (elementId, content, options = {}) {
    const element = document.getElementById(elementId);
    if (!element) return;

    QRCode.toCanvas(element, content, {
        width: options.width || 128,
        margin: 2, // Increased margin
        ...options
    }, function (error) {
        if (error) console.error('QR Code error:', error);
    });
};

/**
 * Global QR Scanner Helper
 * @param {Function} onScanSuccessCallback 
 */
window.openQRScanner = function (onScanSuccessCallback) {
    // Create modal if not exists
    let modal = document.getElementById('qr-scanner-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'qr-scanner-modal';
        modal.innerHTML = `
            <div class="qr-modal-backdrop" onclick="closeQRScanner()"></div>
            <div class="qr-modal-content">
                <div class="qr-modal-header">
                    <h3>Scan QR Code</h3>
                    <button type="button" onclick="closeQRScanner()">&times;</button>
                </div>
                <div class="qr-modal-body" style="position: relative;">
                    <div id="reader" style="width: 100%; min-height: 300px; border-radius: 0.5rem; overflow: hidden;"></div>
                    <div class="scan-overlay"></div>
                </div>
                <div class="qr-modal-footer">
                    <p>Point your camera at a product QR code.<br><small>Ensure good lighting and steady focus.</small></p>
                </div>
            </div>
            <style>
                #qr-scanner-modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; display: flex; align-items: center; justify-content: center; font-family: sans-serif; }
                .qr-modal-backdrop { position: absolute; width: 100%; height: 100%; background: rgba(0,0,0,0.8); backdrop-filter: blur(4px); }
                .qr-modal-content { position: relative; background: white; border-radius: 1.25rem; width: 90%; max-width: 450px; padding: 1.5rem; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); }
                .qr-modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
                .qr-modal-header h3 { margin: 0; font-size: 1.25rem; color: #1e293b; font-weight: 700; }
                .qr-modal-header button { background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 50%; font-size: 1.25rem; cursor: pointer; color: #64748b; display: flex; align-items: center; justify-content: center; }
                .qr-modal-footer { margin-top: 1.25rem; text-align: center; }
                .qr-modal-footer p { margin: 0; font-size: 0.875rem; color: #475569; line-height: 1.5; }
                .qr-modal-footer small { color: #94a3b8; }
                .scan-overlay { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 220px; height: 220px; border: 2px solid #3b82f6; border-radius: 1rem; pointer-events: none; box-shadow: 0 0 0 4000px rgba(0,0,0,0.3); }
                #reader__scan_region { background: white !important; }
            </style>
        `;
        document.body.appendChild(modal);
    } else {
        modal.style.display = 'flex';
    }

    qrScanner = new Html5Qrcode("reader");

    // Improved Configuration
    const config = {
        fps: 20, // Increase FPS for smoother detection
        qrbox: function (viewfinderWidth, viewfinderHeight) {
            let minEdgePercentage = 0.7; // 70%
            let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
            let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
            return {
                width: qrboxSize,
                height: qrboxSize
            };
        },
        aspectRatio: 1.0
    };

    console.log("Starting QR Scanner...");

    qrScanner.start(
        { facingMode: "environment" },
        config,
        (decodedText, decodedResult) => {
            console.log("QR Scaled successfully:", decodedText);
            onScanSuccessCallback(decodedText, decodedResult);
            closeQRScanner();
        },
        (errorMessage) => {
            // Frame-by-frame parse errors (usually 'No MultiFormat Readers were able to decode the image.')
            // We keep this silent to avoid console flooding, but it means the camera is working.
        }
    ).catch((err) => {
        console.error("Unable to start scanning.", err);
        alert("Unable to access camera. Please ensure you have granted camera permissions and are using HTTPS (or localhost).\n\nError: " + err);
    });
};

window.closeQRScanner = function () {
    if (qrScanner) {
        qrScanner.stop().then(() => {
            document.getElementById('qr-scanner-modal').style.display = 'none';
        }).catch((err) => {
            console.error("Error stopping scanner", err);
            document.getElementById('qr-scanner-modal').style.display = 'none';
        });
    } else {
        const modal = document.getElementById('qr-scanner-modal');
        if (modal) modal.style.display = 'none';
    }
};
