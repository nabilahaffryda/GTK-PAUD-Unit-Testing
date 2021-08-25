import { ApolloClient, createHttpLink, from, InMemoryCache } from '@apollo/client/core';
import { onError } from '@apollo/client/link/error';

const errorHandler = onError(({ networkError, response, operation }) => {
  if (networkError && networkError.statusCode === 401) {
    window.location = process.env.VUE_APP_API_URL + `/auth/login`;
  }

  if (operation.operationName === 'GetAkun') {
    response.errors = null;
  }
});

// HTTP connection to the API
const httpLink = createHttpLink({
  // You should use an absolute URL here
  uri: process.env.VUE_APP_API_URL + '/graphql',
});

// Cache implementation
const cache = new InMemoryCache();

// Create the apollo client
const apolloClient = new ApolloClient({
  link: from([errorHandler, httpLink]),
  cache,
});

export default apolloClient;
