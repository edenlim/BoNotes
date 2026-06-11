import { ref } from 'vue';

export function useAuth() {
    const isLoggedIn = ref(false);

    const checkSession = async () => {
        try {
            const response = await fetch('/api/user', {
                method: 'GET',
                headers: { 'Accept': 'application/json' },
                credentials: 'include'
            });

            if (response.ok) {
                isLoggedIn.value = true;
            } else {
                isLoggedIn.value = false;
            }
        } catch (error) {
            console.error("Failed to verify session:", error);
            isLoggedIn.value = false;
        }
    };

    const handleLogout = () => {
        isLoggedIn.value = false;
    };

    return { isLoggedIn, checkSession, handleLogout };
}
