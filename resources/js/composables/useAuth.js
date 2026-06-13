import { ref } from 'vue';

export function useAuth() {
    const isLoggedIn = ref(false);
    const session = ref(null);

    const checkSession = async () => {
        try {
            const response = await fetch('/api/user', {
                method: 'GET',
                headers: { 'Accept': 'application/json' },
                credentials: 'include'
            });

            if (response.ok) {
                const user = await response.json();
                isLoggedIn.value = true;
                session.value = { userid: user.id, name: user.name };
            } else {
                isLoggedIn.value = false;
                session.value = null;
            }
        } catch (error) {
            console.error("Failed to verify session:", error);
            isLoggedIn.value = false;
            session.value = null;
        }
    };

    const handleLogout = () => {
        isLoggedIn.value = false;
        session.value = null;
    };

    return { isLoggedIn, session, checkSession, handleLogout };
}
