// Makes admin panel tables responsive by adding data attributes
document.addEventListener('DOMContentLoaded', function () {
    // Enhanced function to add data-label attributes to all admin tables
    function makeTablesResponsive() {
        console.log('Refreshing admin tables');
        document.querySelectorAll('.admin-table').forEach(function (table) {
            // Cache the headers for this table
            let headerTexts = [];

            // Try to get header texts from the table
            const headerElements = table.querySelectorAll('thead th');
            if (headerElements && headerElements.length) {
                headerTexts = Array.from(headerElements).map(th => th.textContent.trim());
            }

            // If no headers found (might be hidden in mobile view), try to get from existing data-label
            if (headerTexts.length === 0) {
                const firstRow = table.querySelector('tbody tr');
                if (firstRow) {
                    headerTexts = Array.from(firstRow.querySelectorAll('td')).map(td =>
                        td.getAttribute('data-label') || '');
                    // Filter out any empty headers
                    headerTexts = headerTexts.filter(text => text !== '');
                }
            }

            // If we still have no headers, the table might be empty or malformed
            if (headerTexts.length === 0) {
                console.warn('No headers found for table', table);
                return;
            }

            // Add responsive class to table
            table.classList.add('responsive-table');

            // Process each row
            table.querySelectorAll('tbody tr').forEach(function (row) {
                const cells = Array.from(row.querySelectorAll('td'));

                cells.forEach(function (cell, i) {
                    if (i < headerTexts.length) {
                        // Preserve existing content if we need to modify the cell
                        const currentContent = cell.innerHTML;
                        const currentLabel = cell.getAttribute('data-label');

                        // Set data-label attribute for responsive display if not already set
                        // or if it doesn't match expected header
                        if (!currentLabel || currentLabel !== headerTexts[i]) {
                            cell.setAttribute('data-label', headerTexts[i]);
                        }

                        // Special handling for action buttons column
                        if (headerTexts[i] === 'Actions' || headerTexts[i] === 'Action' ||
                            headerTexts[i].includes('Actions') || i === cells.length - 1) {
                            cell.classList.add('action-cell');
                        }

                        // Wrap cell content in a span if it doesn't already have a wrapper
                        // This helps with our flexbox layout in mobile view
                        if (!cell.querySelector('span, div, a, button, input, select, textarea') &&
                            !cell.classList.contains('action-cell')) {
                            cell.innerHTML = '<span>' + currentContent + '</span>';
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

    // Enhanced mutation observer to detect all kinds of table changes
    const observer = new MutationObserver(function (mutations) {
        let shouldRefresh = false;

        mutations.forEach(function (mutation) {
            // Check for added nodes
            if (mutation.addedNodes && mutation.addedNodes.length > 0) {
                for (let i = 0; i < mutation.addedNodes.length; i++) {
                    const node = mutation.addedNodes[i];
                    if (node.nodeType === 1) { // Only process element nodes
                        if (node.classList && node.classList.contains('admin-table')) {
                            shouldRefresh = true;
                            break;
                        } else if (node.querySelector && node.querySelector('.admin-table')) {
                            shouldRefresh = true;
                            break;
                        } else if (node.tagName === 'TD' || node.tagName === 'TR') {
                            shouldRefresh = true;
                            break;
                        }
                    }
                }
            }

            // Also check for attribute changes or removed nodes that might affect tables
            if (!shouldRefresh &&
                (mutation.type === 'attributes' ||
                    mutation.removedNodes.length > 0 ||
                    mutation.target.classList && mutation.target.classList.contains('admin-table'))) {
                shouldRefresh = true;
            }

            if (shouldRefresh) {
                setTimeout(makeTablesResponsive, 10);
                return;
            }
        });
    });

    // Start observing the document with enhanced configuration
    observer.observe(document.body, {
        childList: true,
        subtree: true,
        attributes: true,
        characterData: true
    });

    // Expose function globally to allow manual refresh of table styles
    window.refreshAdminTables = makeTablesResponsive;

    // Force initial update for any tables already in the DOM
    setTimeout(makeTablesResponsive, 300);

    // Add fetch hook to intercept admin API calls and refresh tables afterward
    const originalFetch = window.fetch;
    window.fetch = async function () {
        // Call the original fetch and get the response
        const response = await originalFetch.apply(this, arguments);

        // Check if this is an admin API call
        const url = arguments[0];
        if (typeof url === 'string' &&
            (url.includes('/actions/adminpanel/') || url.includes('/admin/'))) {

            console.log('Admin API call detected:', url);
            // Schedule multiple refreshes to catch both immediate and delayed DOM updates
            setTimeout(makeTablesResponsive, 100);
            setTimeout(makeTablesResponsive, 500);
            setTimeout(makeTablesResponsive, 1000);
        }

        // Return the original response
        return response.clone();
    };

    // Listen for clicks on action buttons and refresh tables afterward
    document.body.addEventListener('click', function (event) {
        // Check if the clicked element is an action button, inside one, or a tab button
        const actionButton = event.target.closest('.admin-action-btn, .promote-btn, .ban-btn, .red-btn, .delete-service-admin-btn');
        const tabButton = event.target.closest('.admin-tab-btn');

        if (actionButton) {
            console.log('Action button clicked, scheduling refresh');
            // Schedule multiple refreshes to catch both immediate and delayed changes
            setTimeout(makeTablesResponsive, 50);
            setTimeout(makeTablesResponsive, 300);
            setTimeout(makeTablesResponsive, 1000);
        } else if (tabButton) {
            // For tab buttons, also schedule refreshes
            setTimeout(makeTablesResponsive, 100);
            setTimeout(makeTablesResponsive, 500);
        }
    });

    // Create a small helper that can be called from the console if needed
    window.fixAdminTables = function () {
        console.log('Manual table fix requested');
        makeTablesResponsive();
    };
});
