(function() {
    'use strict';

    var config = window.boozedWishlist || {};
    var ajaxUrl = config.ajax_url || '';
    var nonce = config.nonce || '';
    var isLoggedIn = !!config.is_logged_in;
    var wishlistBaseUrl = config.wishlist_base_url || '/wishlist/';

    function post(action, data) {
        var body = new URLSearchParams();
        body.set('action', action);
        body.set('nonce', nonce);
        Object.keys(data || {}).forEach(function(key) {
            body.set(key, data[key]);
        });
        return fetch(ajaxUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
            credentials: 'same-origin',
            body: body.toString()
        }).then(function(response) {
            return response.json();
        });
    }

    function initPdpModal() {
        var modal = document.querySelector('[data-wishlist-modal]');
        var trigger = document.querySelector('[data-wishlist-heart]');
        if (!modal || !trigger) {
            return;
        }
        var heartCheckSvg = '<svg viewBox="0 -960 960 960" fill="currentColor" aria-hidden="true"><path d="m424-296 282-282-56-56-226 226-114-114-56 56 170 170Zm56 216q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>';
        var closeButtons = modal.querySelectorAll('[data-wishlist-close]');
        var stateForm = modal.querySelector('[data-wishlist-state="form"]');
        var stateSuccess = modal.querySelector('[data-wishlist-state="success"]');
        var select = modal.querySelector('[data-wishlist-select]');
        var newName = modal.querySelector('[data-wishlist-new-name]');
        var addBtn = modal.querySelector('[data-wishlist-add]');
        var successText = modal.querySelector('[data-wishlist-success-text]');
        var viewLink = modal.querySelector('[data-wishlist-view-link]');
        var lastFocus = null;

        function setHeartActive() {
            trigger.classList.add('is-active');
            trigger.setAttribute('aria-pressed', 'true');
            trigger.innerHTML = heartCheckSvg;
        }

        function showState(name) {
            modal.querySelectorAll('[data-wishlist-state]').forEach(function(node) {
                node.classList.toggle('is-active', node.getAttribute('data-wishlist-state') === name);
            });
        }

        function open() {
            lastFocus = document.activeElement;
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.documentElement.classList.add('wishlist-modal-open');
            showState('form');
            if (select) {
                select.focus();
            }
        }

        function close() {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.documentElement.classList.remove('wishlist-modal-open');
            if (lastFocus && typeof lastFocus.focus === 'function') {
                lastFocus.focus();
            }
        }

        closeButtons.forEach(function(btn) { btn.addEventListener('click', close); });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) {
                close();
            }
        });

        trigger.addEventListener('click', function() {
            if (!isLoggedIn) {
                window.location.href = config.login_url || window.location.href;
                return;
            }
            open();
        });

        if (addBtn) {
            addBtn.addEventListener('click', function() {
                var productId = trigger.getAttribute('data-product-id');
                var selectedWishlistId = select && select.value ? select.value : '';
                var createName = newName && newName.value ? newName.value.trim() : '';
                var createPromise = Promise.resolve(selectedWishlistId);

                if (createName) {
                    createPromise = post('boozed_wishlist_create', { name: createName }).then(function(result) {
                        if (!result.success) {
                            throw new Error((result.data && result.data.message) || 'error');
                        }
                        var lists = (result.data && result.data.wishlists) || [];
                        if (select) {
                            select.innerHTML = '';
                            lists.forEach(function(item) {
                                var opt = document.createElement('option');
                                opt.value = item.id;
                                opt.textContent = item.title;
                                select.appendChild(opt);
                            });
                        }
                        if (newName) {
                            newName.value = '';
                        }
                        return result.data.wishlist ? String(result.data.wishlist.id) : '';
                    });
                }

                createPromise
                    .then(function(wishlistId) {
                        if (!wishlistId && select && select.value) {
                            wishlistId = select.value;
                        }
                        return post('boozed_wishlist_add_product', {
                            wishlist_id: wishlistId,
                            product_id: productId
                        });
                    })
                    .then(function(result) {
                        if (!result.success) {
                            if (result.data && result.data.requires_login && result.data.login_url) {
                                window.location.href = result.data.login_url;
                                return;
                            }
                            throw new Error((result.data && result.data.message) || 'error');
                        }
                        if (successText) {
                            successText.textContent = (result.data && result.data.message) || 'Toegevoegd aan Wenslijst';
                        }
                        if (viewLink) {
                            var nextUrl = (result.data && result.data.wishlist_url) || wishlistBaseUrl;
                            viewLink.setAttribute('href', nextUrl);
                        }
                        setHeartActive();
                        showState('success');
                    })
                    .catch(function(err) {
                        window.alert(err.message || (config.messages && config.messages.generic_error) || 'Er ging iets mis.');
                    });
            });
        }
    }

    function initManagerModal() {
        var manager = document.querySelector('[data-wishlist-manager]');
        if (!manager || !isLoggedIn) {
            return;
        }

        var modal = document.querySelector('[data-wishlist-manager-modal]');
        if (!modal) {
            return;
        }

        var openCreateBtn = manager.querySelector('[data-wishlist-open-create]');
        var closeBtns = modal.querySelectorAll('[data-wishlist-manager-close]');
        var createNameInput = modal.querySelector('[data-manager-create-name]');
        var createSubmit = modal.querySelector('[data-manager-create-submit]');
        var moveTarget = modal.querySelector('[data-manager-move-target]');
        var moveSubmit = modal.querySelector('[data-manager-move-submit]');
        var renameNameInput = modal.querySelector('[data-manager-rename-name]');
        var renameSubmit = modal.querySelector('[data-manager-rename-submit]');
        var deleteSubmit = modal.querySelector('[data-manager-delete-submit]');
        var currentMove = { sourceId: null, productId: null };
        var currentWishlistId = null;
        var quoteModal = document.querySelector('[data-wishlist-quote-modal]');
        var quoteOpenBtn = manager.querySelector('[data-wishlist-quote-open]');
        var quoteCloseBtns = quoteModal ? quoteModal.querySelectorAll('[data-wishlist-quote-close]') : [];
        var quoteSubmit = quoteModal ? quoteModal.querySelector('[data-wishlist-quote-submit]') : null;
        var quoteMessage = quoteModal ? quoteModal.querySelector('[data-wishlist-quote-message]') : null;
        var quoteTitle = quoteModal ? quoteModal.querySelector('[data-wishlist-quote-title]') : null;
        var currentQuoteWishlistId = null;

        function setState(name) {
            modal.querySelectorAll('[data-manager-state]').forEach(function(el) {
                el.classList.toggle('is-active', el.getAttribute('data-manager-state') === name);
            });
        }

        function open(state) {
            setState(state);
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.documentElement.classList.add('wishlist-modal-open');
        }

        function close() {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.documentElement.classList.remove('wishlist-modal-open');
        }

        closeBtns.forEach(function(btn) { btn.addEventListener('click', close); });

        function rebuildFromResponse(lists) {
            var container = manager.querySelector('[data-wishlist-lists]');
            if (!container || !Array.isArray(lists)) {
                window.location.reload();
                return;
            }
            window.location.reload();
        }

        if (openCreateBtn) {
            openCreateBtn.addEventListener('click', function() {
                if (createNameInput) {
                    createNameInput.value = '';
                }
                open('create');
            });
        }

        if (createSubmit) {
            createSubmit.addEventListener('click', function() {
                var name = createNameInput && createNameInput.value ? createNameInput.value.trim() : '';
                if (!name) {
                    return;
                }
                post('boozed_wishlist_create', { name: name }).then(function(result) {
                    if (!result.success) {
                        throw new Error((result.data && result.data.message) || 'error');
                    }
                    close();
                    rebuildFromResponse(result.data && result.data.wishlists);
                }).catch(function(err) {
                    window.alert(err.message || 'Er ging iets mis.');
                });
            });
        }

        manager.addEventListener('click', function(e) {
            var listEl = e.target.closest('[data-wishlist-id]');
            if (!listEl) {
                return;
            }
            var wishlistId = listEl.getAttribute('data-wishlist-id');

            if (e.target.closest('[data-wishlist-delete]')) {
                currentWishlistId = wishlistId;
                open('delete');
                return;
            }

            if (e.target.closest('[data-wishlist-rename]')) {
                currentWishlistId = wishlistId;
                if (renameNameInput) {
                    var currentTitle = listEl.querySelector('h2');
                    renameNameInput.value = currentTitle ? currentTitle.textContent.trim() : '';
                }
                open('rename');
                return;
            }

            var row = e.target.closest('tr[data-product-id]');
            if (!row) {
                return;
            }
            var productId = row.getAttribute('data-product-id');

            if (e.target.closest('[data-wishlist-remove]')) {
                post('boozed_wishlist_remove_product', {
                    wishlist_id: wishlistId,
                    product_id: productId
                }).then(function(result) {
                    if (!result.success) throw new Error((result.data && result.data.message) || 'error');
                    rebuildFromResponse(result.data && result.data.wishlists);
                }).catch(function(err) { window.alert(err.message || 'Er ging iets mis.'); });
                return;
            }

            if (e.target.closest('[data-wishlist-move]')) {
                post('boozed_wishlist_list', {}).then(function(result) {
                    if (!result.success) throw new Error((result.data && result.data.message) || 'error');
                    var lists = (result.data && result.data.wishlists) || [];
                    moveTarget.innerHTML = '';
                    var targetCount = 0;
                    lists.forEach(function(list) {
                        if (String(list.id) === String(wishlistId)) {
                            return;
                        }
                        var opt = document.createElement('option');
                        opt.value = list.id;
                        opt.textContent = list.title;
                        moveTarget.appendChild(opt);
                        targetCount += 1;
                    });
                    if (targetCount === 0) {
                        var emptyOpt = document.createElement('option');
                        emptyOpt.value = '';
                        emptyOpt.textContent = 'Geen andere wenslijsten beschikbaar';
                        moveTarget.appendChild(emptyOpt);
                        moveTarget.disabled = true;
                        if (moveSubmit) {
                            moveSubmit.disabled = true;
                        }
                    } else {
                        moveTarget.disabled = false;
                        if (moveSubmit) {
                            moveSubmit.disabled = false;
                        }
                    }
                    currentMove = { sourceId: wishlistId, productId: productId };
                    open('move');
                }).catch(function(err) { window.alert(err.message || 'Er ging iets mis.'); });
            }
        });

        if (moveSubmit) {
            moveSubmit.addEventListener('click', function() {
                if (!currentMove.sourceId || !currentMove.productId || !moveTarget.value) {
                    return;
                }
                post('boozed_wishlist_move_product', {
                    source_wishlist_id: currentMove.sourceId,
                    target_wishlist_id: moveTarget.value,
                    product_id: currentMove.productId
                }).then(function(result) {
                    if (!result.success) throw new Error((result.data && result.data.message) || 'error');
                    close();
                    rebuildFromResponse(result.data && result.data.wishlists);
                }).catch(function(err) { window.alert(err.message || 'Er ging iets mis.'); });
            });
        }

        if (renameSubmit) {
            renameSubmit.addEventListener('click', function() {
                var name = renameNameInput && renameNameInput.value ? renameNameInput.value.trim() : '';
                if (!name || !currentWishlistId) {
                    return;
                }
                post('boozed_wishlist_rename', { wishlist_id: currentWishlistId, name: name }).then(function(result) {
                    if (!result.success) throw new Error((result.data && result.data.message) || 'error');
                    close();
                    rebuildFromResponse(result.data && result.data.wishlists);
                }).catch(function(err) { window.alert(err.message || 'Er ging iets mis.'); });
            });
        }

        if (deleteSubmit) {
            deleteSubmit.addEventListener('click', function() {
                if (!currentWishlistId) {
                    return;
                }
                post('boozed_wishlist_delete', { wishlist_id: currentWishlistId }).then(function(result) {
                    if (!result.success) throw new Error((result.data && result.data.message) || 'error');
                    close();
                    rebuildFromResponse(result.data && result.data.wishlists);
                }).catch(function(err) { window.alert(err.message || 'Er ging iets mis.'); });
            });
        }

        function openQuoteModal(wishlistId, wishlistTitle) {
            if (!quoteModal) {
                return;
            }
            currentQuoteWishlistId = wishlistId;
            if (quoteTitle) {
                quoteTitle.textContent = 'Vertel ons meer over jouw aanvraag';
                if (wishlistTitle) {
                    quoteTitle.textContent += ' - ' + wishlistTitle;
                }
            }
            if (quoteMessage) {
                quoteMessage.value = '';
            }
            quoteModal.classList.add('is-open');
            quoteModal.setAttribute('aria-hidden', 'false');
            document.documentElement.classList.add('wishlist-modal-open');
            if (quoteMessage) {
                quoteMessage.focus();
            }
        }

        function closeQuoteModal() {
            if (!quoteModal) {
                return;
            }
            quoteModal.classList.remove('is-open');
            quoteModal.setAttribute('aria-hidden', 'true');
            document.documentElement.classList.remove('wishlist-modal-open');
        }

        if (quoteOpenBtn) {
            quoteOpenBtn.addEventListener('click', function() {
                openQuoteModal(
                    this.getAttribute('data-wishlist-id'),
                    this.getAttribute('data-wishlist-title')
                );
            });
        }

        quoteCloseBtns.forEach(function(btn) {
            btn.addEventListener('click', closeQuoteModal);
        });

        if (quoteSubmit) {
            quoteSubmit.addEventListener('click', function() {
                if (!currentQuoteWishlistId) {
                    return;
                }
                post('boozed_wishlist_request_quote', {
                    wishlist_id: currentQuoteWishlistId,
                    message: quoteMessage && quoteMessage.value ? quoteMessage.value.trim() : ''
                }).then(function(result) {
                    if (!result.success) {
                        throw new Error((result.data && result.data.message) || 'Er ging iets mis.');
                    }
                    closeQuoteModal();
                    window.alert((result.data && result.data.message) || 'Aanvraag verstuurd.');
                }).catch(function(err) {
                    window.alert(err.message || 'Er ging iets mis.');
                });
            });
        }
    }

    initPdpModal();
    initManagerModal();
})();
