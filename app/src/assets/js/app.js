/**
 * app/src/assets/js/app.js
 * Main JavaScript for Procrasti-net. Fun stuff
 */

// --- THEME MANAGEMENT ---
// set the color theme before the page fully loads.
(function() {
    var theme = localStorage.getItem('theme');
    if (theme) {
        if (theme === 'dark') {
            document.documentElement.classList.add('pastel-mode'); // Our dark theme name
        } else if (theme !== 'light') {
            document.documentElement.classList.add(theme);
        }
    }
})();

/**
 * Converts text to "Leet Speak" (e.g., 'a' becomes '4').
 */
function toLeetSpeak(text) {
    const leetMap = {
        'a': '4', 'e': '3', 'g': '6', 'i': '1', 'o': '0', 's': '5', 't': '7',
        'A': '4', 'E': '3', 'G': '6', 'I': '1', 'O': '0', 'S': '5', 'T': '7'
    };
    return text.split('').map(char => leetMap[char] || char).join('');
}

/**
 * Applies special visual effects based on
 * user settings stored in body data attributes.
 */
function applyClientFeatures() {
    const body = document.body;

    // Hand-Drawn Mode:
    if (body.dataset.handDrawnMode === 'true') {
        document.documentElement.classList.add('hand-drawn-mode');
    }

    // Leet Speak: Converts all translated text into numbers.
    if (body.dataset.leetSpeak === 'true') {
        document.querySelectorAll('[data-lang]').forEach(el => {
            el.textContent = toLeetSpeak(el.textContent);
        });
    }

    // Sarcastic Comments: Shows a random mean message if tasks are overdue.
    if (body.dataset.sarcasticComments === 'true') {
        const hasOverdue = !!document.querySelector('.task-due-date.overdue'); // Check if any element has 'overdue' class
        if (hasOverdue) {
            const meanMessages = [
                "Wow, another day of failing. Proud of you.",
                "Your to-do list is more like a 'will-never-do' list.",
                "Maybe if you spent less time here, you'd actually finish something.",
                "I'm not mad, just disappointed. Actually, I'm a computer, I don't care."
            ];
            const msg = meanMessages[Math.floor(Math.random() * meanMessages.length)];
            const el = document.createElement('p');
            el.style = "text-align:center; color:var(--accent); font-weight:bold; margin: 1rem 0;";
            el.textContent = msg;
            document.querySelector('main')?.prepend(el);
        }
    }
}

// --- MAIN INITIALIZATION ---
document.addEventListener('DOMContentLoaded', () => {
    
    // --- LANGUAGE SWITCHING ---
    const languageSelect = document.getElementById('language-select');
    
    function updateLanguage(lang) {
        const elements = document.querySelectorAll('[data-lang]');
        elements.forEach(el => {
            const key = el.getAttribute('data-lang');
            // 'languages' variable comes from languages.js
            if (typeof languages !== 'undefined' && languages[lang] && languages[lang][key]) {
                el.textContent = languages[lang][key];
            }
        });
        localStorage.setItem('language', lang);
        if (languageSelect) languageSelect.value = lang;
        
        // Re-apply leet speak if enabled
        if (document.body.dataset.leetSpeak === 'true') {
            elements.forEach(el => el.textContent = toLeetSpeak(el.textContent));
        }
    }

    if (languageSelect) {
        languageSelect.addEventListener('change', (e) => updateLanguage(e.target.value));
    }

    // Set initial language
    const savedLanguage = localStorage.getItem('language') || 'en';
    updateLanguage(savedLanguage);

    // Apply special settings (Hand-drawn, Leet, etc.)
    applyClientFeatures();

    // --- TASK MANAGEMENT (AJAX) ---
    // AI Help: AJAX allows us to update the database without refreshing the whole page.
    
    // Toggle task status
    document.querySelectorAll('.task-toggle-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            try {
                const response = await fetch(form.action, { method: 'POST', body: new FormData(form) });
                if (response.ok) {
                    const data = await response.json();
                    const item = form.closest('.task-item');
                    const checkbox = form.querySelector('.task-checkbox');
                    
                    if (data.status === 'done') {
                        item.classList.add('done');
                        checkbox.textContent = '✅';
                    } else {
                        item.classList.remove('done');
                        checkbox.textContent = '⬜';
                    }
                    // 'updateStats' function should be defined in the view or here
                    if (typeof updateStats === 'function') updateStats();
                }
            } catch (err) { console.error("Toggle failed", err); }
        });
    });

    // --- HABIT CHECKING (AJAX) ---
    document.querySelectorAll('.habit-check-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const formData = new FormData();
            formData.append('id', btn.dataset.id);
            formData.append('csrf', btn.dataset.csrf);
            formData.append('date', new Date().toLocaleDateString('en-CA')); // YYYY-MM-DD

            try {
                const response = await fetch('?page=habit_check', { 
                    method: 'POST', 
                    body: formData, 
                    headers: { 'X-Requested-With': 'XMLHttpRequest' } 
                });
                if (response.ok) {
                    btn.textContent = 'Done!';
                    btn.disabled = true;
                    btn.style.opacity = '0.5';
                    // Update streak count on screen
                    const streakEl = btn.closest('.card').querySelector('.streak strong');
                    if (streakEl) streakEl.textContent = parseInt(streakEl.textContent) + 1;
                }
            } catch (err) { console.error("Habit check failed", err); }
        });
    });

    // --- THEME SWITCHING ---
    const themeSwitcher = document.getElementById('theme-select');
    if (themeSwitcher) {
        themeSwitcher.value = localStorage.getItem('theme') || 'light';
        themeSwitcher.addEventListener('change', (e) => {
            const newTheme = e.target.value;
            // Remove all possible theme classes
            document.documentElement.classList.remove('pastel-mode', 'blue-theme', 'green-theme', 'purple-theme', 'pink-theme', 'orange-theme');
            
            if (newTheme === 'dark') {
                document.documentElement.classList.add('pastel-mode');
            } else if (newTheme !== 'light') {
                document.documentElement.classList.add(newTheme);
            }
            localStorage.setItem('theme', newTheme);
        });
    }

    // --- SKILLS FILTERING ---
    const skillCategory = document.getElementById('skill-category-select');
    const skillSearch = document.getElementById('skill-search-input');
    const skillCards = document.querySelectorAll('.skill-card-item');

    function filterSkills() {
        if (!skillCategory || !skillSearch) return;
        const cat = skillCategory.value.toLowerCase();
        const term = skillSearch.value.toLowerCase();

        skillCards.forEach(card => {
            const cardCat = (card.dataset.category || '').toLowerCase();
            const cardName = (card.dataset.name || '').toLowerCase();
            const matchesCat = cat === '' || cardCat === cat;
            const matchesTerm = term === '' || cardName.includes(term);
            card.style.display = (matchesCat && matchesTerm) ? 'block' : 'none';
        });
    }

    skillCategory?.addEventListener('change', filterSkills);
    skillSearch?.addEventListener('input', filterSkills);
});