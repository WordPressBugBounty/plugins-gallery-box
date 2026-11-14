(function($){
	"use strict";

	jQuery(document).ready(function($) {
		// Safety check: ensure WordPress admin notices are never trapped inside the AI banner
		function relocateBannerNotices() {
			var $banner = $('.gbox-aime-banner');
			if ($banner.length) {
				var $innerNotices = $banner.find('.notice').not('.mg-aime-feedback');
				if ($innerNotices.length) {
					if ($('.wp-header-end').length) {
						$innerNotices.insertAfter('.wp-header-end');
					} else if ($('.wrap > h1, .wrap > h2').length) {
						$innerNotices.insertAfter($('.wrap > h1, .wrap > h2').first());
					} else {
						$innerNotices.insertAfter($banner);
					}
				}
			}
		}
		relocateBannerNotices();
		setTimeout(relocateBannerNotices, 50);
		setTimeout(relocateBannerNotices, 300);

		// Temporary hide on cross button (X) click - visual only, reappears on page reload
		$(document).on('click', '.mg-sales-notice .notice-dismiss', function(e) {
			e.preventDefault();
			var $notice = $(this).closest('.mg-sales-notice');
			$notice.slideUp(250, function() {
				$notice.remove();
			});
			// Do NOT send AJAX dismissal request. It will show again on next page reload.
		});

		// 25-day independent dismiss on clicking "Maybe Later" button
		$(document).on('click', '.mg-sales-notice .mg-sales-dismiss-btn', function(e) {
			e.preventDefault();
			var $btn = $(this);
			var $notice = $btn.closest('.mg-sales-notice');
			var noticeType = $btn.data('notice') || $notice.data('notice-type') || 'offer';

			$notice.slideUp(250, function() {
				$notice.remove();
			});

			var info = (typeof gboxAdminInfo !== 'undefined') ? gboxAdminInfo : ((typeof magicalAdminInfo !== 'undefined') ? magicalAdminInfo : null);

			if (info && info.ajaxurl && info.sales_nonce) {
				$.ajax({
					url: info.ajaxurl,
					type: 'POST',
					data: {
						action: 'gallerybox_dismiss_sales_notice',
						nonce: info.sales_nonce,
						notice: noticeType
					}
				});
			}
		});

		// 1-Click Install & Activate AI Marketing Expert from WordPress.org
		$(document).on('click', '.mg-aime-install-btn', function(e) {
			e.preventDefault();
			var $btn = $(this);
			if ($btn.prop('disabled') || $btn.hasClass('is-loading') || $btn.hasClass('is-installed')) {
				return;
			}

			var $notice = $btn.closest('.mg-sales-notice, .gbox-aime-banner');
			var $feedback = $notice.find('.mg-aime-feedback');
			var $btnText = $btn.find('.mg-sales-btn-text');
			var $btnIcon = $btn.find('.mg-sales-btn-icon');
			var info = (typeof gboxAdminInfo !== 'undefined') ? gboxAdminInfo : ((typeof magicalAdminInfo !== 'undefined') ? magicalAdminInfo : null);
			var i18n = (info && info.i18n) ? info.i18n : {};

			if (!info || !info.ajaxurl || !info.install_nonce) {
				return;
			}

			$btn.prop('disabled', true).addClass('is-loading');
			$btnIcon.html('<span class="mg-aime-spinner" aria-hidden="true"></span>');
			$btnText.text(i18n.installing || 'Installing & Activating...');
			$feedback.hide().removeClass('mg-aime-feedback--success mg-aime-feedback--error').empty();

			$.ajax({
				url: info.ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: 'gallerybox_install_aime_plugin',
					nonce: info.install_nonce
				}
			}).done(function(response) {
				if (response && response.success) {
					var msg = (response.data && response.data.message) ? response.data.message : (i18n.installed || 'Installed & Activated!');
					$btn.removeClass('is-loading').addClass('is-installed');
					$btnIcon.text('✅');
					$btnText.text(i18n.installed || 'Installed & Activated!');

					$feedback
						.addClass('mg-aime-feedback--success')
						.text(msg)
						.slideDown(200);

					// Offer clean page reload button and hide "Maybe Later"
					$notice.find('.mg-sales-dismiss-btn').fadeOut(150);
					if (!$notice.find('.mg-aime-reload-btn').length) {
						$('<button type="button" class="mg-sales-btn mg-sales-btn-secondary gbox-aime-btn gbox-aime-btn-secondary mg-aime-reload-btn"></button>')
							.text(i18n.reloadLabel || 'Reload Page')
							.on('click', function() {
								window.location.reload();
							})
							.insertAfter($btn);
					}
				} else {
					var errMsg = (response && response.data && response.data.message) ? response.data.message : (i18n.installError || 'Installation failed.');
					$btn.prop('disabled', false).removeClass('is-loading');
					$btnIcon.text('⚡');
					$btnText.text(i18n.retryLabel || 'Try Again');
					$feedback
						.addClass('mg-aime-feedback--error')
						.text(errMsg)
						.slideDown(200);
				}
			}).fail(function() {
				$btn.prop('disabled', false).removeClass('is-loading');
				$btnIcon.text('⚡');
				$btnText.text(i18n.retryLabel || 'Try Again');
				$feedback
					.addClass('mg-aime-feedback--error')
					.text(i18n.installError || 'Installation failed. Please install AI Marketing Expert from WordPress.org.')
					.slideDown(200);
			});
		});
	});

})(jQuery);
