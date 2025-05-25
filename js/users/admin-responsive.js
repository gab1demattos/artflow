document.addEventListener('DOMContentLoaded', function () {
    function makeTablesResponsive() {
        console.log('Refreshing admin tables');
        document.querySelectorAll('.admin-table').forEach(function (table) {
            let headerTexts = [];

            const headerElements = table.querySelectorAll('thead th');
            if (headerElements && headerElements.length) {
                headerTexts = Array.from(headerElements).map(th => th.textContent.trim());
            }

            if (headerTexts.length === 0) {
                const firstRow = table.querySelector('tbody tr');
                if (firstRow) {
                    headerTexts = Array.from(firstRow.querySelectorAll('td')).map(td =>
                        td.getAttribute('data-label') || '');
                    headerTexts = headerTexts.filter(text => text !== '');
                }
            }

            if (headerTexts.length === 0) {
                console.warn('No headers found for table', table);
                return;
            }

            table.classList.add('responsive-table');

            table.querySelectorAll('tbody tr').forEach(function (row) {
                const cells = Array.from(row.querySelectorAll('td'));

                cells.forEach(function (cell, i) {
                    if (i < headerTexts.length) {
                        const currentContent = cell.innerHTML;
                        const currentLabel = cell.getAttribute('data-label');

                        if (!currentLabel || currentLabel !== headerTexts[i]) {
                            cell.setAttribute('data-label', headerTexts[i]);
                        }

                        if (headerTexts[i] === 'Actions' || headerTexts[i] === 'Action' ||
                            headerTexts[i].includes('Actions') || i === cells.length - 1) {
                            cell.classList.add('action-cell');
                        }

                        if (!cell.querySelector('span, div, a, button, input, select, textarea') &&
                            !cell.classList.contains('action-cell')) {
                            cell.innerHTML = '<span>' + currentContent + '</span>';
                        }
                    }
                });
            });
        });
    }

    makeTablesResponsive();

    function setupMobileCategoryModal() {
        const categoryModal = document.getElementById('category-modal');
        const categoryOverlay = document.getElementById('category-modal-overlay');
        const categoryForm = document.getElementById('category-form');

        if (categoryModal && categoryForm) {
            categoryForm.addEventListener('submit', function () {
                categoryModal.style.height = '';
                categoryModal.style.minHeight = '';
            });

            const openModalBtn = document.getElementById('open-category-modal');
            if (openModalBtn) {
                openModalBtn.addEventListener('click', function () {
                    setTimeout(() => {
                        categoryModal.style.height = 'auto';
                        const formContainer = categoryModal.querySelector('.form-container');
                        if (formContainer) {
                            formContainer.style.paddingBottom = '0';
                        }
                    }, 10);
                });
            }

            const closeModalBtn = document.getElementById('close-category-modal');
            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', function () {
                    resetCategoryModal();
                });
            }

            if (categoryOverlay) {
                categoryOverlay.addEventListener('click', function (e) {
                    if (e.target === categoryOverlay) {
                        resetCategoryModal();
                    }
                });
            }

            function resetCategoryModal() {
                categoryModal.style.height = '';
                categoryModal.style.minHeight = '';
                categoryModal.style.maxHeight = '';

                if (categoryForm) {
                    categoryForm.reset();
                }

                const formContainer = categoryModal.querySelector('.form-container');
                if (formContainer) {
                    formContainer.style.paddingBottom = '';
                }
            }

            window.addEventListener('resize', function () {
                if (categoryOverlay && !categoryOverlay.classList.contains('hidden')) {
                    categoryModal.style.height = 'auto';
                }
            });
        }
    }

    setupMobileCategoryModal();

    function setMobileViewportHeight() {
        let vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);

        const categoryModal = document.getElementById('category-modal');
        const categoryOverlay = document.getElementById('category-modal-overlay');

        if (categoryModal && categoryOverlay && !categoryOverlay.classList.contains('hidden')) {
            categoryModal.style.maxHeight = `calc(90 * var(--vh, 1vh))`;
        }
    }

    setMobileViewportHeight();

    window.addEventListener('resize', setMobileViewportHeight);
    window.addEventListener('orientationchange', setMobileViewportHeight);

    document.querySelectorAll('.admin-tab-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            document.body.classList.add('tab-transitioning');

            setTimeout(() => {
                document.body.classList.remove('tab-transitioning');
            }, 300);

            setTimeout(makeTablesResponsive, 150);
        });
    });

    const observer = new MutationObserver(function (mutations) {
        let shouldRefresh = false;

        mutations.forEach(function (mutation) {
            if (mutation.addedNodes && mutation.addedNodes.length > 0) {
                for (let i = 0; i < mutation.addedNodes.length; i++) {
                    const node = mutation.addedNodes[i];
                    if (node.nodeType === 1) { 
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

    observer.observe(document.body, {
        childList: true,
        subtree: true,
        attributes: true,
        characterData: true
    });

    window.refreshAdminTables = makeTablesResponsive;

    setTimeout(makeTablesResponsive, 300);

    const originalFetch = window.fetch;
    window.fetch = async function () {
        const response = await originalFetch.apply(this, arguments);

        const url = arguments[0];
        if (typeof url === 'string' &&
            (url.includes('../../actions/adminpanel/') || url.includes('/admin/'))) {

            console.log('Admin API call detected:', url);
            // Schedule multiple refreshes to catch both immediate and delayed DOM updates
            setTimeout(makeTablesResponsive, 100);
            setTimeout(makeTablesResponsive, 500);
            setTimeout(makeTablesResponsive, 1000);
        }

        return response.clone();
    };

    document.body.addEventListener('click', function (event) {
        const actionButton = event.target.closest('.admin-action-btn, .promote-btn, .ban-btn, .red-btn, .delete-service-admin-btn');
        const tabButton = event.target.closest('.admin-tab-btn');

        if (actionButton) {
            console.log('Action button clicked, scheduling refresh');
            setTimeout(makeTablesResponsive, 50);
            setTimeout(makeTablesResponsive, 300);
            setTimeout(makeTablesResponsive, 1000);
        } else if (tabButton) {
            setTimeout(makeTablesResponsive, 100);
            setTimeout(makeTablesResponsive, 500);
        }
    });

    window.fixAdminTables = function () {
        console.log('Manual table fix requested');
        makeTablesResponsive();
    };
});
