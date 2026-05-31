# Testing Checklist

## Manual Functional Testing

- Verify the homepage loads without PHP warnings or fatal errors.
- Verify the desktop layout shows three columns with tabs, content slider, and paired image slider.
- Verify the mobile layout switches to accordion cards below `992px`.
- Verify the first category is expanded by default on mobile.
- Verify tab clicks swap the active category on desktop.
- Verify slide pagination updates content and image together on desktop.
- Verify touch swipe works inside the mobile slider.
- Verify only active categories with active slides appear on the frontend.

## Category CRUD Testing

- Create a new category using an existing icon path.
- Create a new category using an uploaded icon file.
- Edit a category title and confirm the frontend updates.
- Toggle a category from active to inactive and verify it disappears from the frontend.
- Delete a category and verify related slides are removed because of the foreign key cascade.
- Submit the category form without a title and verify validation appears.
- Submit the category form with an invalid CSRF token and verify the request is rejected.

## Slide CRUD Testing

- Create a slide using an existing image path.
- Create a slide using an uploaded image file.
- Edit badge text, title, button text, and sort order.
- Change slide sort order and confirm the frontend slide sequence updates.
- Toggle a slide from active to inactive and confirm it disappears from the frontend.
- Delete a slide and verify its uploaded image is removed when applicable.
- Submit a slide without selecting a category and verify validation appears.
- Upload an invalid file type and verify the upload is blocked.

## Responsive Testing

- Test layout behavior at `1200px`, `992px`, `768px`, and `576px`.
- Verify heading sizes, spacing, and card proportions remain balanced across breakpoints.
- Verify there is no horizontal overflow on mobile.
- Verify accordion transitions remain smooth when switching between categories.
- Verify slider bullets remain visible and clickable on both desktop and mobile.

## Accessibility Testing

- Navigate desktop tabs using keyboard arrow keys.
- Verify visible focus states on tabs, accordion triggers, and admin form buttons.
- Confirm accordion buttons announce expanded and collapsed states.
- Confirm all meaningful images include alt text where appropriate.
- Confirm decorative icons are marked as non-essential to assistive technologies.

## Regression and QA Checks

- Verify no broken image URLs in seeded content.
- Verify no console errors in browser developer tools.
- Verify file upload directories remain writable after deployment.
- Verify admin links, cancel buttons, and status actions redirect correctly.
- Verify the SQL import completes cleanly on a fresh database.
