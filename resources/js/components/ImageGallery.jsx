import React, { useState, useEffect } from 'react';
import { ChevronLeft, ChevronRight, X, Download, ZoomIn, ZoomOut } from 'lucide-react';

const ImageGallery = ({ images = [] }) => {
  const [selectedIndex, setSelectedIndex] = useState(null);
  const [zoomLevel, setZoomLevel] = useState(1);

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
  }, [selectedIndex]);

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
    if (direction === 'prev') {
      setSelectedIndex((prev) => (prev > 0 ? prev - 1 : images.length - 1));
    } else {
      setSelectedIndex((prev) => (prev < images.length - 1 ? prev + 1 : 0));
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

  if (images.length === 0) {
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
        {images.map((image, index) => (
          <div
            key={index}
            className="group relative cursor-pointer overflow-hidden rounded-lg shadow-md hover:shadow-xl
                     aspect-square bg-gray-200 transition-all duration-300 transform hover:-translate-y-1"
            onClick={() => openModal(index)}
          >
            <img
              src={image.image_path}
              alt={image.alt_text}
              className="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent
                          opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
            <div className="absolute inset-0 flex items-center justify-center opacity-0
                          group-hover:opacity-100 transition-opacity duration-300">
              <ZoomIn className="w-8 h-8 text-white" />
            </div>
          </div>
        ))}
      </div>

      {/* Modal */}
      {selectedIndex !== null && (
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
              onClick={() => window.open(images[selectedIndex].image_path, '_blank')}
              className="p-2 rounded-full bg-white/10 hover:bg-white/20 text-white
                       transition-colors duration-200"
              title="Download"
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
              src={images[selectedIndex]?.image_path}
              alt={images[selectedIndex]?.alt_text}
              className="w-full h-auto max-h-[85vh] object-contain transition-transform duration-300"
              style={{ transform: `scale(${zoomLevel})` }}
            />
            <div className="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-primary to-transparent">
              <div className="p-6 text-white">
                <h3 className="text-xl font-semibold mb-2">{images[selectedIndex]?.title}</h3>
                <p className="text-sm opacity-90">{images[selectedIndex]?.alt_text}</p>
                <p className="text-xs mt-2 opacity-75">
                  Image {selectedIndex + 1} of {images.length}
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

export default ImageGallery;
