import time
from playwright.sync_api import sync_playwright

with sync_playwright() as p:
    browser = p.chromium.launch(headless=True)
    page = browser.new_page(viewport={"width": 1280, "height": 1200})

    # 1. Events Listing Page
    page.goto("http://localhost:8080/events", wait_until="networkidle")
    time.sleep(1)
    page.screenshot(path="/home/jules/verification/events_listing_v3.png", full_page=False)

    # 2. Event Detail Page with Start/End Time, Event Type, Photo & Video Gallery
    page.goto("http://localhost:8080/events/sample-masterclass-2026", wait_until="networkidle")
    time.sleep(1)
    page.screenshot(path="/home/jules/verification/event_detail_v3.png", full_page=False)

    browser.close()
print("Verification screenshots captured successfully.")
