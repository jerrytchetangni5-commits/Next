FROM node:22-slim

RUN apt-get update && apt-get install -y \
    wget \
    gnupg \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm install

RUN npx playwright install --with-deps chromium

COPY . .

CMD ["npm", "run", "scrape:hub"]