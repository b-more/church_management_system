import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import ImageGallery from './components/ImageGallery';

// Keep the Example component import if you're using it elsewhere
import './components/Example';

// Initialize React Gallery component
if (document.getElementById('gallery-root')) {
    const element = document.getElementById('gallery-root');
    const root = createRoot(element);

    // Wrap the render in a try-catch to help debug any issues
    try {
        root.render(
            <React.StrictMode>
                <ImageGallery images={window.galleryImages || []} />
            </React.StrictMode>
        );
    } catch (error) {
        console.error('Error rendering gallery:', error);
    }
}
