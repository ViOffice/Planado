/*
 * SPDX-FileCopyrightText: 2021 Weymeirsch und Langer GbR
 *
 * SPDX-License-Identifier: AGPL-3.0-only
 */

function CopyToClipboard(selector) {
    // Select object (has to be text field)
    const copyText = document.querySelector(selector);
    // make sure, text field is displayed
    copyText.style.display = 'initial';
    // Select and copy text field
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices
    document.execCommand("copy");
    // hide object again
    copyText.style.display = 'none';
}

/* Implement Eventlistener in specified site like this:
 * Why Event-Listener? -> Firefox blocks calling copy directly
 * Instead, copying should trigger via event listeners
 * https://developer.mozilla.org/en-US/docs/Mozilla/Add-ons/WebExtensions/Interact_with_the_clipboard
 *
 * document.querySelector("#trigger").addEventListener("click", CopyToClipboard);
 */
