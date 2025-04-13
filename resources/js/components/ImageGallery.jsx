import React, { useState, useEffect } from 'react';
import { ChevronLeft, ChevronRight, X, Download, ZoomIn, ZoomOut } from 'lucide-react';
import ReactDOM from 'react-dom';

const ImageGallery = () => {
  const [images, setImages] = useState([]);
  const [filteredImages, setFilteredImages] = useState([]);
  const [selectedIndex, setSelectedIndex] = useState(null);
  const [zoomLevel, setZoomLevel] = useState(1);
  const [category, setCategory] = useState('all');
  const [isLoading, setIsLoading] = useState(true);

  // Initialize with images from window if available
  useEffect(() => {
    if (window.galleryImages && window.galleryImages.length > 0) {
      console.log("Loading gallery images:", window.galleryImages);
      setImages(window.galleryImages);
      setFilteredImages(window.galleryImages);
    }
    setIsLoading(false);
  }, []);

  // Listen for keyboard navigation
  useEffect(() => {
    const handleKeyDown = (e) => {
      if (selectedIndex === null) return;

      switch(e.key) {
        case 'ArrowLeft':
          navigate('prev');
          break;
        case 'ArrowRight':
          navigate('next');
          break;
        case 'Escape':
          closeModal();
          break;
        default:
          break;
      }
    };

    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, [selectedIndex, filteredImages]);

  // Listen for filter events from the parent page
  useEffect(() => {
    const handleFilterEvent = (event) => {
      const newCategory = event.detail.category;
      setCategory(newCategory);

      if (newCategory === 'all') {
        setFilteredImages(images);
      } else {
        // Assuming your images have a 'category' field
        // Modify this according to your actual data structure
        const filtered = images.filter(img => {
          // If image has categories field, filter by it
          if (img.category) {
            return img.category.toLowerCase() === newCategory;
          }
          // If image has tags array, check if category is in tags
          if (img.tags && Array.isArray(img.tags)) {
            return img.tags.some(tag => tag.toLowerCase() === newCategory);
          }
          // If no way to filter, include all images
          return true;
        });
        setFilteredImages(filtered);
      }
    };

    document.addEventListener('filterGallery', handleFilterEvent);
    return () => document.removeEventListener('filterGallery', handleFilterEvent);
  }, [images]);

  const openModal = (index) => {
    setSelectedIndex(index);
    setZoomLevel(1);
    document.body.style.overflow = 'hidden';
  };

  const closeModal = () => {
    setSelectedIndex(null);
    setZoomLevel(1);
    document.body.style.overflow = 'auto';
  };

  const navigate = (direction) => {
    if (filteredImages.length === 0) return;

    if (direction === 'prev') {
      setSelectedIndex((prev) => (prev > 0 ? prev - 1 : filteredImages.length - 1));
    } else {
      setSelectedIndex((prev) => (prev < filteredImages.length - 1 ? prev + 1 : 0));
    }
    setZoomLevel(1);
  };

  const handleZoom = (action) => {
    setZoomLevel(prev => {
      if (action === 'in' && prev < 3) return prev + 0.5;
      if (action === 'out' && prev > 0.5) return prev - 0.5;
      return prev;
    });
  };

  // Helper function to get correct image URL
  const getImageUrl = (image) => {
    // Check which property has the image URL/path
    if (image.image_url) {
      return image.image_url; // From your getImageUrlAttribute accessor
    } else if (image.image_path) {
      // If it's just a path, we need to prepend the storage URL
      if (image.image_path.startsWith('http')) {
        return image.image_path;
      }
      return `/storage/${image.image_path}`;
    }
    return ''; // Fallback
  };

  if (isLoading) {
    return (
      <div className="text-center py-12">
        <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary mx-auto mb-4"></div>
        <p className="text-gray-600">Loading gallery images...</p>
      </div>
    );
  }

  if (filteredImages.length === 0) {
    return (
      <div className="text-center py-12">
        <p className="text-gray-500 text-lg">No images available in the gallery.</p>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      {/* Gallery Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        {filteredImages.map((image, index) => (
          <div
            key={index}
            className="group relative cursor-pointer overflow-hidden rounded-lg shadow-md hover:shadow-xl
                     aspect-square bg-gray-200 transition-all duration-300 transform hover:-translate-y-1"
            onClick={() => openModal(index)}
          >
            <img
              src={getImageUrl(image)}
              alt={image.alt_text || image.title || 'Gallery image'}
              className="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent
                          opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
            <div className="absolute inset-0 p-4 flex flex-col justify-end opacity-0 group-hover:opacity-100 transition-opacity duration-300">
              <h3 className="text-white font-bold text-lg truncate">{image.title}</h3>
              {image.event_date && (
                <p className="text-white/90 text-sm">
                  {new Date(image.event_date).toLocaleDateString()}
                </p>
              )}
            </div>
          </div>
        ))}
      </div>

      {/* Modal */}
      {selectedIndex !== null && filteredImages[selectedIndex] && (
        <div className="fixed inset-0 bg-primary/95 flex items-center justify-center z-50 p-4">
          <div className="absolute top-4 right-4 flex gap-4">
            <button
              onClick={() => handleZoom('in')}
              className="p-2 rounded-full bg-white/10 hover:bg-white/20 text-white
                       transition-colors duration-200"
              title="Zoom In"
            >
              <ZoomIn className="w-6 h-6" />
            </button>
            <button
              onClick={() => handleZoom('out')}
              className="p-2 rounded-full bg-white/10 hover:bg-white/20 text-white
                       transition-colors duration-200"
              title="Zoom Out"
            >
              <ZoomOut className="w-6 h-6" />
            </button>
            <button
              onClick={() => window.open(getImageUrl(filteredImages[selectedIndex]), '_blank')}
              className="p-2 rounded-full bg-white/10 hover:bg-white/20 text-white
                       transition-colors duration-200"
              title="View Full Image"
            >
              <Download className="w-6 h-6" />
            </button>
            <button
              onClick={closeModal}
              className="p-2 rounded-full bg-white/10 hover:bg-white/20 text-white
                       transition-colors duration-200"
              title="Close"
            >
              <X className="w-6 h-6" />
            </button>
          </div>

          <button
            onClick={() => navigate('prev')}
            className="absolute left-4 top-1/2 -translate-y-1/2 p-3 rounded-full
                     bg-white/10 hover:bg-white/20 text-white transition-colors duration-200"
          >
            <ChevronLeft className="w-6 h-6" />
          </button>

          <div className="relative max-w-6xl w-full mx-auto">
            <img
              src={getImageUrl(filteredImages[selectedIndex])}
              alt={filteredImages[selectedIndex]?.alt_text || filteredImages[selectedIndex]?.title || 'Gallery image'}
              className="w-full h-auto max-h-[85vh] object-contain transition-transform duration-300"
              style={{ transform: `scale(${zoomLevel})` }}
            />
            <div className="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-primary to-transparent">
              <div className="p-6 text-white">
                <h3 className="text-xl font-semibold mb-2">{filteredImages[selectedIndex]?.title}</h3>
                <p className="text-sm opacity-90">{filteredImages[selectedIndex]?.description || filteredImages[selectedIndex]?.alt_text}</p>
                <p className="text-xs mt-2 opacity-75">
                  Image {selectedIndex + 1} of {filteredImages.length}
                </p>
              </div>
            </div>
          </div>

          <button
            onClick={() => navigate('next')}
            className="absolute right-4 top-1/2 -translate-y-1/2 p-3 rounded-full
                     bg-white/10 hover:bg-white/20 text-white transition-colors duration-200"
          >
            <ChevronRight className="w-6 h-6" />
          </button>
        </div>
      )}
    </div>
  );
};

// Render the component to the DOM if the mount point exists
if (document.getElementById('gallery-root')) {
  ReactDOM.render(<ImageGallery />, document.getElementById('gallery-root'));
}

export default ImageGallery;
