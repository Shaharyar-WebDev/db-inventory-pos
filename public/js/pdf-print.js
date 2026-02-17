window.printPdf = function (pdfBase64, title) {
    const byteCharacters = atob(pdfBase64);
    const byteNumbers = new Uint8Array(byteCharacters.length);
    for (let i = 0; i < byteCharacters.length; i++) {
        byteNumbers[i] = byteCharacters.charCodeAt(i);
    }
    const blob = new Blob([byteNumbers], { type: 'application/pdf' });
    const blobUrl = URL.createObjectURL(blob);

    const printWindow = window.open('', '_blank', 'width=1200,height=800');

    printWindow.document.write(
        '<!DOCTYPE html>' +
        '<html>' +
        '<head>' +
            '<title>' + title + '</title>' +
            '<style>' +
                '* { margin: 0; padding: 0; }' +
                'html, body { width: 100%; height: 100%; }' +
                'iframe {' +
                    'position: fixed;' +
                    'top: 0; left: 0;' +
                    'width: 100%; height: 100%;' +
                    'border: none;' +
                '}' +
                '@media print {' +
                    'body { background: white; }' +
                    'iframe { position: static; width: 100%; height: 100%; }' +
                '}' +
            '</style>' +
        '</head>' +
        '<body>' +
            '<iframe id="pdfFrame" src="' + blobUrl + '"></iframe>' +
        '</body>' +
        '</html>'
    );

    printWindow.document.close();

    const frame = printWindow.document.getElementById('pdfFrame');

    frame.onload = function () {
        printWindow.addEventListener('keydown', function (e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                e.stopPropagation();
                try {
                    frame.contentWindow.focus();
                    frame.contentWindow.print();
                } catch (err) {
                    printWindow.focus();
                    printWindow.print();
                }
            }
        });

        setTimeout(() => {
            printWindow.requestAnimationFrame(() => {
                printWindow.requestAnimationFrame(() => {
                    try {
                        frame.contentWindow.focus();
                        frame.contentWindow.print();
                    } catch (e) {
                        printWindow.focus();
                        printWindow.print();
                    }
                    setTimeout(() => URL.revokeObjectURL(blobUrl), 10000);
                });
            });
        }, 1000);
    };
};
