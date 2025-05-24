// Makes admin panel tables responsive by adding data attributes
document.addEventListener('DOMContentLoaded', function () {
    // Function to add data-label attributes to all admin tables
    function makeTablesResponsive() {
        document.querySelectorAll('.admin-table').forEach(function (table) {
            // Get header texts and skip the last column if it's 'Actions'
            const headerTexts = Array.from(table.querySelectorAll('thead th')).map(th => {
                const text = th.textContent.trim();
                return text;
            });

            // Add responsive class to table
            table.classList.add('responsive-table');

            table.querySelectorAll('tbody tr').forEach(function (row) {
                Array.from(row.querySelectorAll('td')).forEach(function (cell, i) {
                    if (i < headerTexts.length) {
                        // Set data-label attribute for responsive display
                        cell.setAttribute('data-label', headerTexts[i]);

                        // Special handling for action buttons column
                        if (headerTexts[i] === 'Actions' || i === headerTexts.length - 1) {
                            cell.classList.add('action-cell');
                        }
                    }
                });
            });
        });
    }

    // Apply immediately when loaded
    makeTablesResponsive();

    // Also apply when tab content changes
    document.querySelectorAll('.admin-tab-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            // Give tables time to render
            setTimeout(makeTablesResponsive, 100);
        });
    });

    // Mutation observer to detect dynamically added tables
    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (mutation.addedNodes && mutation.addedNodes.length > 0) {
                // Check for our tables in the added content
                for (let i = 0; i < mutation.addedNodes.length; i++) {
                    const node = mutation.addedNodes[i];
                    if (node.nodeType === 1) { // Only process element nodes
                        if (node.classList && node.classList.contains('admin-table')) {
                            makeTablesResponsive();
                        } else if (node.querySelector && node.querySelector('.admin-table')) {
                            makeTablesResponsive();
                        }
                    }
                }
            }
        });
    });

    // Start observing the document with configured parameters
    observer.observe(document.body, { childList: true, subtree: true });
});
