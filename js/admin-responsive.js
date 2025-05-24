// Makes admin panel tables responsive by adding data attributes
document.addEventListener('DOMContentLoaded', function () {
    // Function to add data-label attributes to all admin tables
    function makeTablesResponsive() {
        document.querySelectorAll('.admin-table').forEach(function (table) {
            // Get header texts
            const headerTexts = Array.from(table.querySelectorAll('thead th')).map(th => {
                const text = th.textContent.trim();
                return text;
            });

            // Add responsive class to table
            table.classList.add('responsive-table');

            // Set data labels for each cell
            table.querySelectorAll('tbody tr').forEach(function (row) {
                Array.from(row.querySelectorAll('td')).forEach(function (cell, i) {
                    if (i < headerTexts.length) {
                        // Set data-label attribute for responsive display
                        cell.setAttribute('data-label', headerTexts[i]);

                        // Special handling for action buttons column
                        if (headerTexts[i] === 'Actions' || headerTexts[i] === 'Action' ||
                            headerTexts[i].includes('Actions') || i === headerTexts.length - 1) {
                            cell.classList.add('action-cell');
                        }

                        // Wrap cell content in a span if it doesn't already have a wrapper
                        // This helps with our flexbox layout in mobile view
                        if (!cell.querySelector('span, div, a, button') &&
                            !cell.classList.contains('action-cell')) {
                            const content = cell.innerHTML;
                            cell.innerHTML = '<span>' + content + '</span>';
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
            // Add transition class to body for smooth tab switching
            document.body.classList.add('tab-transitioning');

            // Remove transitioning class after animation completes
            setTimeout(() => {
                document.body.classList.remove('tab-transitioning');
            }, 300);

            // Give tables time to render
            setTimeout(makeTablesResponsive, 150);
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

    // Expose function globally to allow manual refresh of table styles
    window.refreshAdminTables = makeTablesResponsive;

    // Force initial update for any tables already in the DOM
    setTimeout(makeTablesResponsive, 300);
});
