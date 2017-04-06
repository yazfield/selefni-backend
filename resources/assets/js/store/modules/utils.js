export class Token {
    constructor(rawToken) {
        if(!rawToken) {
            throw new Error('Token must not be null');
        }
        if(typeof rawToken === 'string') {
            rawToken = JSON.parse(rawToken);
        }
        this.type = rawToken.token_type;
        this.expiresIn = rawToken.expires_in;
        this.expiresAt = Date.now() + rawToken.expires_in;
        this.accessToken = rawToken.access_token;
        this.refreshToken = rawToken.refresh_token;
    }

    isValid() {
        return this.expiresAt > Date.now();
    }
}