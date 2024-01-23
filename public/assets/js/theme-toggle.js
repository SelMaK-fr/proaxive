(function () {
    const Theme = { LIGHT: 'default', DARK: 'dark' };
    const THEME_STORAGE_KEY = 'layout_mode';
    const THEME_OWNER = document.documentElement;

    const cachedTheme = localStorage.getItem(THEME_STORAGE_KEY);
    if (cachedTheme) {
        THEME_OWNER.dataset[THEME_STORAGE_KEY] = cachedTheme;
    }

    document.addEventListener('DOMContentLoaded', () => {
        const themeToggle = document.getElementById('theme-toggle');
        if (!themeToggle) return;

        let darkThemeSystemPreference;

        const setIsTogglePressed = (isPressed) => themeToggle.setAttribute('aria-pressed', isPressed);

        const toggleTheme = () => {
            const oldTheme = THEME_OWNER.dataset[THEME_STORAGE_KEY];
            const newTheme = oldTheme === Theme.LIGHT ? Theme.DARK : Theme.LIGHT;
            THEME_OWNER.dataset[THEME_STORAGE_KEY] = newTheme;
            setIsTogglePressed(newTheme === Theme.DARK);
            localStorage.setItem(THEME_STORAGE_KEY, newTheme);
            darkThemeSystemPreference?.removeEventListener?.('change', handleSystemDarkThemePreferenceChange);
        };

        const handleSystemDarkThemePreferenceChange = ({ matches: isDarkThemePreferred }) => {
            setIsTogglePressed(isDarkThemePreferred);
        };

        if (!cachedTheme) {
            darkThemeSystemPreference = window.matchMedia('(prefers-color-scheme: dark)');
            darkThemeSystemPreference.addEventListener?.('change', handleSystemDarkThemePreferenceChange);
        }

        setIsTogglePressed(cachedTheme === Theme.DARK || !!darkThemeSystemPreference?.matches);
        themeToggle.addEventListener('click', toggleTheme);
    });
})();