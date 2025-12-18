@props(['listing', 'listingType'])

<script>
document.addEventListener('DOMContentLoaded', function() {
    const listingId = {{ $listing->id }};
    const listingType = '{{ $listingType }}';

    // Track contact clicks
    document.querySelectorAll('[data-track-contact]').forEach(el => {
        el.addEventListener('click', function() {
            if (typeof posthog !== 'undefined') {
                posthog.capture('contact_clicked', {
                    listing_id: listingId,
                    listing_type: listingType,
                    contact_type: this.dataset.trackContact
                });
            }
        });
    });

    // Track image gallery clicks
    document.querySelectorAll('[data-track-image]').forEach(el => {
        el.addEventListener('click', function() {
            if (typeof posthog !== 'undefined') {
                posthog.capture('image_gallery_clicked', {
                    listing_id: listingId,
                    listing_type: listingType,
                    image_index: parseInt(this.dataset.trackImage)
                });
            }
        });
    });

    // Track share button
    const shareBtn = document.getElementById('share-listing');
    if (shareBtn) {
        shareBtn.addEventListener('click', async function() {
            const shareData = {
                title: '{{ addslashes($listing->name ?? $listing->title ?? $listing->business_name) }}',
                url: window.location.href
            };

            if (navigator.share) {
                try {
                    await navigator.share(shareData);
                    if (typeof posthog !== 'undefined') {
                        posthog.capture('listing_shared', {
                            listing_id: listingId,
                            listing_type: listingType,
                            share_method: 'native'
                        });
                    }
                } catch (err) {
                    // User cancelled share
                }
            } else {
                try {
                    await navigator.clipboard.writeText(window.location.href);
                    if (typeof posthog !== 'undefined') {
                        posthog.capture('listing_shared', {
                            listing_id: listingId,
                            listing_type: listingType,
                            share_method: 'copy_link'
                        });
                    }
                    // Show feedback
                    const originalText = shareBtn.innerHTML;
                    shareBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Link Copied!';
                    setTimeout(() => {
                        shareBtn.innerHTML = originalText;
                    }, 2000);
                } catch (err) {
                    // Fallback for older browsers
                    alert('Copy this link: ' + window.location.href);
                }
            }
        });
    }
});
</script>
