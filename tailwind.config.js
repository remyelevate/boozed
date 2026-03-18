/** @type {import('tailwindcss').Config} */
module.exports = {
  safelist: [
    'aspect-[314/435]',
    'aspect-[535/688]',
    'aspect-[688/535]',
    'md:w-1/3',
    '!bg-brand-coral',
    'bg-brand-coral',
    'hover:!opacity-90',
    'pr-section-x',
    'md:pr-section-x',
    // Offerte progress circles (JS toggles these)
    '!bg-brand-purple',
    '!border-brand-purple',
    '!text-brand-white',
    'bg-brand-white',
    'border-brand-border',
    'text-gray-400',
    'text-brand-purple',
  ],
  content: [
    './*.php',
    './resources/views/**/*.php',
    './app/**/*.php',
    './assets/js/offerte-aanvraag.js',
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          white: '#FFFFFF',
          black: '#000000',
          purple: '#312783',
          indigo: '#0C0A21',
          coral: '#E83F44',
          nude: '#DDA692',
          red: '#C41E3A',
          border: '#E5E7EB',
          'border-focus': '#312783',
          'progress-track': '#F7E9E4',
        },
      },
      fontFamily: {
        heading: ['Nexa', 'sans-serif'],
        body: ['Poppins', 'sans-serif'],
      },
      fontSize: {
        /* Headings – mobile (default) */
        'h1': ['2.5rem', { lineHeight: '1.2' }],      /* 40px */
        'h2': ['2.25rem', { lineHeight: '1.2' }],      /* 36px */
        'h3': ['2rem', { lineHeight: '1.2' }],         /* 32px */
        'h4': ['1.5rem', { lineHeight: '1.4' }],       /* 24px */
        'h5': ['1.25rem', { lineHeight: '1.4' }],     /* 20px */
        'h6': ['1.125rem', { lineHeight: '1.4' }],     /* 18px */
        /* Headings – desktop (use with md:) */
        'h1-lg': ['3.5rem', { lineHeight: '1.2' }],   /* 56px */
        'h2-lg': ['3rem', { lineHeight: '1.2' }],     /* 48px */
        'h3-lg': ['2.5rem', { lineHeight: '1.2' }],   /* 40px */
        'h4-lg': ['2rem', { lineHeight: '1.3' }],     /* 32px */
        'h5-lg': ['1.5rem', { lineHeight: '1.4' }],   /* 24px */
        'h6-lg': ['1.25rem', { lineHeight: '1.4' }],  /* 20px */
        /* Body (Poppins) – line height 150% */
        'tagline': ['1rem', { lineHeight: '1.5' }],
        'discount': ['1rem', { lineHeight: '1.5' }],
        'body-lg': ['1.25rem', { lineHeight: '1.5' }],   /* 20px */
        'body-md': ['1.125rem', { lineHeight: '1.5' }],  /* 18px */
        'body': ['1rem', { lineHeight: '1.5' }],         /* 16px */
        'body-sm': ['0.875rem', { lineHeight: '1.5' }],  /* 14px */
        'body-xs': ['0.75rem', { lineHeight: '1.5' }],   /* 12px */
      },
      maxWidth: {
        section: '1920px',
      },
      spacing: {
        'section-x': 'var(--section-padding-x)',
        'section-y': 'var(--section-padding-y)',
      },
      fontWeight: {
        extrabold: '800',
        bold: '700',
        semibold: '600',
        medium: '500',
        normal: '400',
        light: '300',
      },
    },
  },
  plugins: [],
};
