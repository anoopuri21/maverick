<x-filament-widgets::widget>
    <div style="background: linear-gradient(135deg, #1a1a1a 0%, #d97706 100%); border-radius: 12px; padding: 2rem; color: white; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h2 style="font-size: 1.75rem; font-weight: 700; margin: 0 0 0.5rem 0; color: white;">
                    Welcome back, {{ auth()->user()->name ?? 'Admin' }} 👋
                </h2>
                <p style="font-size: 1rem; opacity: 0.9; margin: 0; color: white;">
                    Manage your Maverick Business Academy website content, programs & settings from here.
                </p>
            </div>
            <div style="text-align: right;">
                <a href="{{ url('/') }}" target="_blank"
                   style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); padding: 0.75rem 1.5rem; border-radius: 8px; color: white; text-decoration: none; font-weight: 500; transition: all 0.2s;"
                   onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                   onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                        <polyline points="15 3 21 3 21 9"/>
                        <line x1="10" y1="14" x2="21" y2="3"/>
                    </svg>
                    View Live Website
                </a>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>