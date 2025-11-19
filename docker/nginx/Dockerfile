# ---- STAGE 1: Build Node.js dependencies ----
FROM node:22-alpine AS node-build

# Set working dir
WORKDIR /var/www/html

# Copy code
COPY . .

# Install packages from the lock file
RUN npm ci

# Build the static files
RUN npm run build
RUN npm run build-rtl

# ---- STAGE 2: Production image ----
FROM nginx:alpine

# Install envsubst (part of gettext package)
RUN apk add --no-cache gettext

# Set working directory
WORKDIR /var/www/html

# Copy code form node-build in build stage
COPY --from=node-build /var/www/html/public /var/www/html/public

# Change the owner of the static files folder
RUN chown -R nginx:nginx /var/www/html

# Copy nginx template config
COPY docker/nginx/nginx.conf.template /etc/nginx/templates/nginx.conf.template

# Copy entrypoint script
COPY docker/nginx/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set entrypoint to custom script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Start nginx as usual
CMD ["nginx", "-g", "daemon off;"]